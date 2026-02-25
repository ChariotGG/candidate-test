'use strict';

/** ============================ Beam Analysis Data Type ============================ */

/**
 * Beam material specification.
 * @param {String} name         Material name
 * @param {Object} properties   Material properties {EI (N·mm²), j2}
 */
class Material {
    constructor(name, properties) {
        this.name = name;
        this.properties = properties;
    }
}

/**
 * @param {Number} primarySpan     Beam primary span length (m)
 * @param {Number} secondarySpan   Beam secondary span length (m)
 * @param {Material} material      Beam material object
 */
class Beam {
    constructor(primarySpan, secondarySpan, material) {
        this.primarySpan = primarySpan;
        this.secondarySpan = secondarySpan;
        this.material = material;
    }
}

/** ============================ Beam Analysis Class ============================ */

class BeamAnalysis {
    constructor() {
        this.options = {
            condition: 'simply-supported'
        };

        this.analyzer = {
            'simply-supported': new BeamAnalysis.analyzer.simplySupported(),
            'two-span-unequal': new BeamAnalysis.analyzer.twoSpanUnequal()
        };
    }

    getDeflection(beam, load, condition) {
        var analyzer = this.analyzer[condition];
        if (analyzer) {
            return { beam, load, equation: analyzer.getDeflectionEquation(beam, load) };
        } else {
            throw new Error('Invalid condition');
        }
    }

    getBendingMoment(beam, load, condition) {
        var analyzer = this.analyzer[condition];
        if (analyzer) {
            return { beam, load, equation: analyzer.getBendingMomentEquation(beam, load) };
        } else {
            throw new Error('Invalid condition');
        }
    }

    getShearForce(beam, load, condition) {
        var analyzer = this.analyzer[condition];
        if (analyzer) {
            return { beam, load, equation: analyzer.getShearForceEquation(beam, load) };
        } else {
            throw new Error('Invalid condition');
        }
    }
}

/** ============================ Beam Analysis Analyzers ============================ */

BeamAnalysis.analyzer = {};

/**
 * Simply Supported Beam under UDL
 *
 * Sign convention (matches Excel "1. Simply Supported UDL"):
 *   Shear:    V(x) = wL/2 − w·x                            
 *   Moment:   M(x) = −(w·x·(L−x)/2)                        
 *   Deflection: δ(x) = −(w·x·(L³−2Lx²+x³)/(24·EI)) · j2 · 1000   
 *
 * EI input: N·mm²  →  divide by 1×10⁹ to get kN·m²
 */
BeamAnalysis.analyzer.simplySupported = class {
    constructor() {}

    getShearForceEquation(beam, load) {
        const L = beam.primarySpan;   // m
        const w = load;               // kN/m
        return function (x) {
            return { x, y: (w * L / 2) - w * x };
        };
    }

    getBendingMomentEquation(beam, load) {
        const L = beam.primarySpan;
        const w = load;
        return function (x) {
            return { x, y: -(w * x * (L - x)) / 2 };
        };
    }

    getDeflectionEquation(beam, load) {
        const L  = beam.primarySpan;
        const w  = load;
        const EI = beam.material.properties.EI / 1e9;   
        const j2 = beam.material.properties.j2 || 1;

        return function (x) {
            const y = -(w * x * (Math.pow(L, 3) - 2 * L * Math.pow(x, 2) + Math.pow(x, 3)) / (24 * EI)) * j2 * 1000;
            return { x, y };
        };
    }
};


/**
 * Two-Span Unequal Continuous Beam under UDL (both spans)
 *
 * Three-Moment Equation (Clapeyron) with Ma = Mc = 0:
 *   Mb = w·(L1³ + L2³) / (8·(L1 + L2))    [hogging, positive magnitude]
 *
 * Reactions:
 *   Ra  = wL1/2 − Mb/L1
 *   Rc  = wL2/2 − Mb/L2
 *   Rb  = w(L1+L2) − Ra − Rc
 *   Rb2 = wL2/2 + Mb/L2   [reaction at B from span-2 side]
 *
 * Shear (global x from A):
 *   0 ≤ x ≤ L1  →  V = Ra − w·x
 *   L1 < x ≤ L1+L2  →  V = Rb2 − w·(x−L1)
 *
 * Bending Moment (sagging = negative, Excel convention):
 *   Span 1: M = −(Ra·x − w·x²/2)
 *   Span 2: M = −(Rb2·xp − w·xp²/2 − Mb)   where xp = x − L1
 *             = Mb − Rb2·xp + w·xp²/2
 *             [at B: xp=0 → M = Mb (hogging, shown positive)]
 *
 * Deflection (double integration, per-span, downward = negative, in mm):
 *   Span 1 constants:
 *     C1 = −Ra·L1²/6 + w·L1³/24
 *     EI·y1 = Ra·x³/6 − w·x⁴/24 + C1·x
 *   Span 2 constants:
 *     D1 = −Rb2·L2²/6 + w·L2³/24 + Mb·L2/2
 *     EI·y2 = Rb2·xp³/6 − w·xp⁴/24 − Mb·xp²/2 + D1·xp
 *   Output: δ = −(EI·y / EI_kNm2) · j2 · 1000   [mm]
 */
BeamAnalysis.analyzer.twoSpanUnequal = class {
    constructor() {}

    _getParams(beam, load) {
        const L1 = beam.primarySpan;
        const L2 = beam.secondarySpan;
        const w  = load;
        const EI = beam.material.properties.EI / 1e9;   // kN·m²
        const j2 = beam.material.properties.j2 || 1;

        // Support moment at B
        const Mb  = w * (Math.pow(L1, 3) + Math.pow(L2, 3)) / (8 * (L1 + L2));

        // Reactions
        const Ra  = w * L1 / 2 - Mb / L1;
        const Rc  = w * L2 / 2 - Mb / L2;
        const Rb  = w * (L1 + L2) - Ra - Rc;
        const Rb2 = w * L2 / 2 + Mb / L2;

        // Integration constants
        const C1 = -Ra  * Math.pow(L1, 2) / 6 + w * Math.pow(L1, 3) / 24;
        const D1 = -Rb2 * Math.pow(L2, 2) / 6 + w * Math.pow(L2, 3) / 24 + Mb * L2 / 2;

        return { L1, L2, w, EI, j2, Mb, Ra, Rb, Rc, Rb2, C1, D1 };
    }

    getShearForceEquation(beam, load) {
        const { L1, w, Ra, Rb2 } = this._getParams(beam, load);
        return function (x) {
            const y = x <= L1
                ? Ra - w * x
                : Rb2 - w * (x - L1);
            return { x, y };
        };
    }

    getBendingMomentEquation(beam, load) {
        const { L1, w, Ra, Rb2, Mb } = this._getParams(beam, load);
        return function (x) {
            let y;
            if (x <= L1) {
                y = -(Ra * x - w * x * x / 2);
            } else {
                const xp = x - L1;
                y = -(Rb2 * xp - w * xp * xp / 2 - Mb);
            }
            return { x, y };
        };
    }

    getDeflectionEquation(beam, load) {
        const { L1, w, Ra, Rb2, Mb, EI, j2, C1, D1 } = this._getParams(beam, load);
        return function (x) {
            let raw;
            if (x <= L1) {
                raw = Ra  * Math.pow(x, 3) / 6
                    - w   * Math.pow(x, 4) / 24
                    + C1  * x;
            } else {
                const xp = x - L1;
                raw = Rb2 * Math.pow(xp, 3) / 6
                    - w   * Math.pow(xp, 4) / 24
                    - Mb  * Math.pow(xp, 2) / 2
                    + D1  * xp;
            }
            const y = -(raw / EI) * j2 * 1000;
            return { x, y };
        };
    }
};