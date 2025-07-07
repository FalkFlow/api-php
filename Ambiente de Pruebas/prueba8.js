import http from 'k6/http';
import { check, sleep } from 'k6';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

export let options = {
    vus: 10,            // 10 usuarios simultáneos
    iterations: 10,     // cada VU envía una solicitud
    thresholds: {
        http_req_failed: ['rate<0.01'], // menos del 1% de errores
    },
};

// Variaciones de stock para simular conflicto
const posiblesStocks = [100, 200, 250, 300, 350, 400, 450, 500, 550, 600];

export default function () {
    const stock = posiblesStocks[Math.floor(Math.random() * posiblesStocks.length)];

    const payload = JSON.stringify({
        codigo_producto: "0303456",
        marca: "Bauker",
        Precio: 75000,
        stock: stock
    });

    const headers = { 'Content-Type': 'application/json' };

    const res = http.put('http://localhost:8000/api/manuales/0303456', payload, { headers });

    check(res, {
        '✅ Status 200 o 201': (r) => r.status === 200 || r.status === 201,
        '📦 Respuesta incluye stock correcto': (r) => r.body.includes(`"stock":${stock}`),
    });

    sleep(1);
}

export function handleSummary(data) {
    return {
        'prueba8.json': JSON.stringify(data),
        stdout: textSummary(data, { indent: '→ ', enableColors: true }),
    };
}
