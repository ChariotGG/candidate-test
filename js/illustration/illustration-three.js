import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

// ─── Scene ────────────────────────────────────────────────────────────────────
const scene = new THREE.Scene();
scene.background = new THREE.Color(0x000000);

// ─── Camera ───────────────────────────────────────────────────────────────────
const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.01, 1000);

// ─── Renderer ─────────────────────────────────────────────────────────────────
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setPixelRatio(window.devicePixelRatio);
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;

const container = document.getElementById('illustration-3d-container');
if (container) container.appendChild(renderer.domElement);
else document.body.appendChild(renderer.domElement);

// ─── Lighting ─────────────────────────────────────────────────────────────────
scene.add(new THREE.AmbientLight(0xffffff, 0.55));

const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
dirLight.position.set(-5, 10, 6);
dirLight.castShadow = true;
dirLight.shadow.mapSize.set(2048, 2048);
scene.add(dirLight);

const dirLight2 = new THREE.DirectionalLight(0xffffff, 0.35);
dirLight2.position.set(6, 4, -4);
scene.add(dirLight2);

// ─── Textures ─────────────────────────────────────────────────────────────────
const texLoader = new THREE.TextureLoader();

function makeMat(repeatX, repeatY) {
  const color  = texLoader.load('model/wood/wood.fbm/Colormap.png');
  const normal = texLoader.load('model/wood/wood.fbm/NormalMap.png');
  color.wrapS  = color.wrapT  = THREE.RepeatWrapping;
  normal.wrapS = normal.wrapT = THREE.RepeatWrapping;
  color.repeat.set(repeatX, repeatY);
  normal.repeat.set(repeatX, repeatY);
  return new THREE.MeshStandardMaterial({
    map: color, normalMap: normal,
    roughness: 0.85, metalness: 0.0,
  });
}

function makePlankMats(lenX, widZ, H) {
  return [
    makeMat(widZ,  H * 15),
    makeMat(widZ,  H * 15),
    makeMat(lenX,  widZ),
    makeMat(lenX,  widZ),
    makeMat(lenX,  H * 15),
    makeMat(lenX,  H * 15),
  ];
}

function buildPlank(lenX, widZ, H, cx, cy, cz) {
  const geo  = new THREE.BoxGeometry(lenX, H, widZ);
  const mesh = new THREE.Mesh(geo, makePlankMats(lenX, widZ, H));
  mesh.position.set(cx, cy, cz);
  mesh.castShadow    = true;
  mesh.receiveShadow = true;
  scene.add(mesh);
  return mesh;
}

// ─── Plank Parameters ─────────────────────────────────────────────────────────
const H = 0.2;
const W = 0.5;
const GAP = 0.02;
const halfGap = GAP / 2;

const plankA_lenX = W;
const plankA_lenZ = 2;
const plankA_cx = -plankA_lenX / 2;
const plankA_cz = plankA_lenZ / 2;

const plankB_lenX = W;
const plankB_lenZ = 3;
const plankB_cx = plankB_lenX / 2 + halfGap;
const plankB_cz = plankB_lenZ / 2;

const plankC_lenX = 0.4;
const plankC_lenZ = 2;
const plankC_cx = 0.01;
const plankC_cz = plankC_lenZ / 2 - 0.02;

// ─── Build Planks ─────────────────────────────────────────────────────────────
buildPlank(plankA_lenX, plankA_lenZ, H, plankA_cx, H / 2, plankA_cz);
buildPlank(plankB_lenX, plankB_lenZ, H, plankB_cx, H / 2, plankB_cz);
buildPlank(plankC_lenX, plankC_lenZ, H, plankC_cx, H + H / 2, plankC_cz);

// ─── Geometry Bounds (CALCULATED FROM PLANK CENTERS + HALF-SIZES) ─────────────
// Plank A: center (-0.25, 0.1, 1.0), size (0.5×0.2×2)
const AX_MIN = plankA_cx - plankA_lenX/2, AX_MAX = plankA_cx + plankA_lenX/2;
const AZ_MIN = plankA_cz - plankA_lenZ/2, AZ_MAX = plankA_cz + plankA_lenZ/2;

// Plank B: center (0.26, 0.1, 1.5), size (0.5×0.2×3)
const BX_MIN = plankB_cx - plankB_lenX/2, BX_MAX = plankB_cx + plankB_lenX/2;
const BZ_MIN = plankB_cz - plankB_lenZ/2, BZ_MAX = plankB_cz + plankB_lenZ/2;

// Plank C: center (0.01, 0.3, 0.98), size (0.4×0.2×2)
const CX_MIN = plankC_cx - plankC_lenX/2, CX_MAX = plankC_cx + plankC_lenX/2;
const CZ_MIN = plankC_cz - plankC_lenZ/2, CZ_MAX = plankC_cz + plankC_lenZ/2;

const TY = H;       // top of bottom planks (0.2)
const TYC = H * 2;  // top of plank C (0.4)

// ─── Dot Helper (FIXED: size scaled to plank dimensions, precise positioning) ─
function createCornerDot(x, y, z, baseSize = 0.018) {
  // Dot size proportional to smallest plank dimension (H=0.2)
  // baseSize ~9% of H for good visual balance
  const size = baseSize;
  
  const material = new THREE.MeshBasicMaterial({ 
    color: 0xffffff, 
    depthTest: true,
    depthWrite: false,
  });
  
  const geometry = new THREE.SphereGeometry(size, 12, 12);
  const mesh = new THREE.Mesh(geometry, material);
  
  mesh.position.set(x, y, z);
  mesh.renderOrder = 10;
  mesh.frustumCulled = false;
  
  return mesh;
}

// Helper to add corner dots for a plank given its bounds
function addPlankCornerDots(xMin, xMax, yBottom, yTop, zMin, zMax) {
  // Top face corners (y = yTop)
  scene.add(createCornerDot(xMin, yTop, zMin));
  scene.add(createCornerDot(xMin, yTop, zMax));
  scene.add(createCornerDot(xMax, yTop, zMin));
  scene.add(createCornerDot(xMax, yTop, zMax));
  
  // Bottom face corners (y = yBottom)
  scene.add(createCornerDot(xMin, yBottom, zMin));
  scene.add(createCornerDot(xMin, yBottom, zMax));
  scene.add(createCornerDot(xMax, yBottom, zMin));
  scene.add(createCornerDot(xMax, yBottom, zMax));
}

// ─── Dimension Line Helper (UPDATED: uses fixed createCornerDot) ─────────────
function dimensionLine(p1, p2, labelText, labelOffset) {
  const grp = new THREE.Group();

  // Line
  const mat = new THREE.LineBasicMaterial({ color: 0xffffff, depthTest: true, depthWrite: false });
  const geo = new THREE.BufferGeometry().setFromPoints([
    new THREE.Vector3(...p1),
    new THREE.Vector3(...p2)
  ]);
  const line = new THREE.Line(geo, mat);
  line.renderOrder = 1;
  grp.add(line);

  // Dots at endpoints (using size scaled to scene)
  const dot1 = createCornerDot(...p1, 0.018);
  grp.add(dot1);
  const dot2 = createCornerDot(...p2, 0.018);
  grp.add(dot2);

  // Label at midpoint + offset
  const mid = [
    (p1[0] + p2[0]) / 2 + (labelOffset ? labelOffset[0] : 0),
    (p1[1] + p2[1]) / 2 + (labelOffset ? labelOffset[1] : 0),
    (p1[2] + p2[2]) / 2 + (labelOffset ? labelOffset[2] : 0),
  ];

  const canvas = document.createElement('canvas');
  canvas.width = 192; canvas.height = 60;
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, 192, 60);
  ctx.font = 'bold 26px Arial';
  ctx.fillStyle = '#ffffff';
  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
  ctx.fillText(labelText, 96, 30);
  const tex = new THREE.CanvasTexture(canvas);
  const sp = new THREE.Sprite(new THREE.SpriteMaterial({
    map: tex, transparent: true, depthTest: true, depthWrite: false
  }));
  sp.scale.set(0.52, 0.163, 1);
  sp.position.set(...mid);
  sp.renderOrder = 20;
  grp.add(sp);

  scene.add(grp);
  return grp;
}

// ─── Corner Dots for Each Plank (using calculated bounds) ─────────────────────
// Plank A corners (Y: 0 to 0.2)
addPlankCornerDots(AX_MIN, AX_MAX, 0, TY, AZ_MIN, AZ_MAX);

// Plank B corners (Y: 0 to 0.2)
addPlankCornerDots(BX_MIN, BX_MAX, 0, TY, BZ_MIN, BZ_MAX);

// Plank C corners (Y: 0.2 to 0.4)
addPlankCornerDots(CX_MIN, CX_MAX, TY, TYC, CZ_MIN, CZ_MAX);

// ─── Dimension Lines ──────────────────────────────────────────────────────────

// Plank A: length (2m) along Z - left side
dimensionLine(
  [AX_MIN, TY * 0.5, AZ_MIN],
  [AX_MIN, TY * 0.5, AZ_MAX],
  '2m',
  [-0.18, 0, 0]
);

// Plank A: width (0.5m) along X - front
dimensionLine(
  [AX_MIN, 0, AZ_MIN],
  [AX_MAX, 0, AZ_MIN],
  '0.5m',
  [0, -0.12, -0.18]
);

// Plank A: height (0.2m) along Y - left front corner
dimensionLine(
  [AX_MIN, 0, AZ_MIN],
  [AX_MIN, TY, AZ_MIN],
  '0.2m',
  [-0.22, 0, 0]
);

// Plank B: length (3m) along Z - right side
dimensionLine(
  [BX_MAX, TY * 0.5, BZ_MIN],
  [BX_MAX, TY * 0.5, BZ_MAX],
  '3m',
  [0.18, 0, 0]
);

// Plank B: width (0.5m) along X - front
dimensionLine(
  [BX_MIN, 0, BZ_MIN],
  [BX_MAX, 0, BZ_MIN],
  '0.5m',
  [0, -0.12, -0.18]
);

// Plank B: height (0.2m) along Y - right front corner
dimensionLine(
  [BX_MAX, 0, BZ_MAX],
  [BX_MAX, TY, BZ_MAX],
  '0.2m',
  [0.22, 0, 0]
);

// Plank C: length (2m) along Z - top
dimensionLine(
  [CX_MAX, TYC, CZ_MIN],
  [CX_MAX, TYC, CZ_MAX],
  '2m',
  [0.18, 0.12, 0]
);

// Plank C: width (0.4m) along X - top side
dimensionLine(
  [CX_MIN, TYC, CZ_MIN],
  [CX_MAX, TYC, CZ_MIN],
  '0.4m',
  [0, 0.12, -0.15]
);

// Plank C: height (0.2m) along Y - right side
dimensionLine(
  [CX_MAX, TY, CZ_MAX],
  [CX_MAX, TYC, CZ_MAX],
  '0.2m',
  [0.22, 0, 0]
);

// Gap between A and B (0.02m) - in X direction at middle Z
dimensionLine(
  [AX_MAX, TY, (AZ_MIN + AZ_MAX) / 2],
  [BX_MIN, TY, (BZ_MIN + BZ_MAX) / 2],
  '0.02m',
  [0, 0.12, 0]
);

// Total width A+gap+B = 0.54m along X front bottom
dimensionLine(
  [AX_MIN, 0, BZ_MIN - 0.08],
  [BX_MAX, 0, BZ_MIN - 0.08],
  '0.54m',
  [0, -0.12, -0.18]
);

// ─── Axis Overlay (Canvas-based, screen space) ────────────────────────────────
const axisCanvas = document.createElement('canvas');
axisCanvas.style.position = 'fixed';
axisCanvas.style.top = '10px';
axisCanvas.style.right = '10px';
axisCanvas.style.pointerEvents = 'none';
axisCanvas.style.zIndex = '100';
axisCanvas.width = 100;
axisCanvas.height = 100;
document.body.appendChild(axisCanvas);

function drawAxisOverlay() {
  const ctx = axisCanvas.getContext('2d');
  ctx.clearRect(0, 0, 100, 100);

  const cx = 50, cy = 55;
  const len = 35;

  const axes = [
    { dir: new THREE.Vector3(1, 0, 0), color: '#ff4444', label: 'X' },
    { dir: new THREE.Vector3(0, 1, 0), color: '#44ff44', label: 'Y' },
    { dir: new THREE.Vector3(0, 0, 1), color: '#4466ff', label: 'Z' },
  ];

  const viewMatrix = camera.matrixWorldInverse;

  const projected = axes.map(ax => {
    const v = ax.dir.clone().applyMatrix4(new THREE.Matrix4().extractRotation(viewMatrix));
    return {
      x: v.x * len,
      y: -v.y * len,
      color: ax.color,
      label: ax.label,
      depth: v.z,
    };
  });

  projected.sort((a, b) => b.depth - a.depth);

  projected.forEach(ax => {
    const ex = cx + ax.x;
    const ey = cy + ax.y;

    ctx.beginPath();
    ctx.moveTo(cx, cy);
    ctx.lineTo(ex, ey);
    ctx.strokeStyle = ax.color;
    ctx.lineWidth = 2;
    ctx.stroke();

    ctx.font = 'bold 13px Arial';
    ctx.fillStyle = ax.color;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    const lx = cx + ax.x * 1.22;
    const ly = cy + ax.y * 1.22;
    ctx.fillText(ax.label, lx, ly);
  });
}

// ─── Camera ───────────────────────────────────────────────────────────────────
camera.position.set(-4, 3.5, 5);
camera.lookAt(0.5, 0.2, 1);

// ─── OrbitControls ────────────────────────────────────────────────────────────
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;
controls.dampingFactor = 0.05;
controls.target.set(0.5, 0.2, 1);
controls.minDistance = 2;
controls.maxDistance = 15;
controls.update();

// ─── Resize ──────────────────────────────────────────────────────────────────
window.addEventListener('resize', () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
});

// ─── Render Loop ──────────────────────────────────────────────────────────────
function animate() {
  requestAnimationFrame(animate);
  controls.update();
  renderer.render(scene, camera);
  drawAxisOverlay();
}
animate();