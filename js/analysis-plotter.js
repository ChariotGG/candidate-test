'use strict';

/**
 * AnalysisPlotter - plots beam analysis results on a canvas using Chart.js
 * Requires Chart.js to be loaded.
 */
class AnalysisPlotter {
    constructor(canvasId) {
        this.canvasId = canvasId;
        this.chart = null;
    }

    /**
     * Plot the result from BeamAnalysis.getDeflection / getBendingMoment / getShearForce
     * @param {Object} data   { beam, load, equation }
     */
    plot(data) {
        const { beam, equation } = data;

        const totalLength = isNaN(beam.secondarySpan) || beam.secondarySpan <= 0
            ? beam.primarySpan
            : beam.primarySpan + beam.secondarySpan;

        const numPoints = 200;
        const points = [];

        for (let i = 0; i <= numPoints; i++) {
            const x = (i / numPoints) * totalLength;
            const result = equation(x);
            points.push({ x: parseFloat(result.x.toFixed(4)), y: parseFloat(result.y.toFixed(6)) });
        }

        const canvas = document.getElementById(this.canvasId);
        if (!canvas) return;

        // Destroy existing chart
        if (this.chart) {
            this.chart.destroy();
        }

        const ctx = canvas.getContext('2d');

        // Determine label from canvas id
        let label = 'Value';
        let yLabel = '';
        let color = 'rgb(54, 162, 235)';
        if (this.canvasId.includes('deflection')) {
            label = 'Deflection (mm)';
            yLabel = 'Deflection (mm)';
            color = 'rgb(54, 162, 235)';
        } else if (this.canvasId.includes('shear')) {
            label = 'Shear Force (kN)';
            yLabel = 'Shear Force (kN)';
            color = 'rgb(255, 99, 132)';
        } else if (this.canvasId.includes('bending')) {
            label = 'Bending Moment (kNm)';
            yLabel = 'Bending Moment (kNm)';
            color = 'rgb(75, 192, 75)';
        }

        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    label,
                    data: points,
                    borderColor: color,
                    backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: {
                        type: 'linear',
                        title: { display: true, text: 'Position (m)' }
                    },
                    y: {
                        title: { display: true, text: yLabel }
                    }
                },
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y.toFixed(4)}`
                        }
                    }
                }
            }
        });
    }
}