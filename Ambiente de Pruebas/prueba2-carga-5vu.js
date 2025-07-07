import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend } from 'k6/metrics';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

let tiempo_respuesta = new Trend('tiempo_respuesta');

export let options = {
    vus: 5,
    duration: '1m',
    gracefulStop: '10s',
    thresholds: {
        http_req_duration: ['p(95)<800'],
        http_req_failed: ['rate<0.1'],
    },
};

const nombres = ['Taladro', 'Sierra', 'Destornillador', 'Martillo', 'Lijadora'];
const marcas = ['Bauker', 'Dewalt', 'Bosch', 'Makita', 'Stanley'];

function generarProducto() {
    let timestamp = Date.now() + Math.floor(Math.random() * 10000);
    let nombre = nombres[Math.floor(Math.random() * nombres.length)];
    let marca = marcas[Math.floor(Math.random() * marcas.length)];

    return {
        codigo_producto: `P-${timestamp}`,
        marca: marca,
        codigo: `COD-${timestamp}`,
        nombre: nombre,
        precio: Math.floor(Math.random() * 100000) + 10000,
        stock: Math.floor(Math.random() * 500) + 1
    };
}

export default function () {
    let producto = generarProducto();

    let res = http.post(
        'http://localhost:8000/api/manuales',
        JSON.stringify(producto),
        { headers: { 'Content-Type': 'application/json' } }
    );

    tiempo_respuesta.add(res.timings.duration);

    check(res, {
        '✅ Status 200 o 201': (r) => r.status === 200 || r.status === 201,
        '⏱️ Tiempo < 800ms': (r) => r.timings.duration < 800,
        '📦 Contiene mensaje de éxito': (r) => r.body.includes("creado con exito"),
    });

    sleep(1);
}

export function handleSummary(data) {
    return {
        'resultado_stress_5.json': JSON.stringify(data),
        stdout: textSummary(data, { indent: '→ ', enableColors: true }),
    };
}
