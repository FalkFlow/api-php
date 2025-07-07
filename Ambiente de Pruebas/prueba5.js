import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend } from 'k6/metrics';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

let tiempo_respuesta = new Trend('tiempo_respuesta');

export let options = {
    vus: 5,             // usuarios virtuales
    duration: '1m',     // tiempo total de prueba
    gracefulStop: '5s',
    thresholds: {
        http_req_duration: ['avg<700'],
        http_req_failed: ['rate<0.01'],
    },
};

// Configuración del test
const productoId = 1;
const codigoProducto = "0303456";
const cantidad = 5;
const sucursal = "central";

export default function () {
    // Consultar stock actual
    const consulta = http.get(`http://localhost:8000/api/herramientas/${productoId}`);
    const data = consulta.json();

    const stockDisponible = data?.manual?.stock;

    // Validar que el stock sea suficiente y mayor a 0
    if (typeof stockDisponible === 'number' && stockDisponible >= cantidad) {
        const payload = JSON.stringify({
            tipo: "manual",
            codigo_producto: codigoProducto,
            cantidad: cantidad,
            sucursal: sucursal
        });

        const res = http.post('http://localhost:8000/api/stock/descontar', payload, {
            headers: { 'Content-Type': 'application/json' }
        });

        tiempo_respuesta.add(res.timings.duration);

        check(res, {
            '✅ Status 200 OK': (r) => r.status === 200,
            '⏱️ Tiempo < 700ms': (r) => r.timings.duration < 700,
            '📦 Mensaje de éxito': (r) => r.body.includes("Stock transferido correctamente"),
        });

    } else {
        console.log(`🚫 Stock insuficiente (${stockDisponible}) — operación cancelada`);
    }

    sleep(1); // Pausa entre intentos
}

export function handleSummary(data) {
    return {
        'prueba5.json': JSON.stringify(data),
        stdout: textSummary(data, { indent: '→ ', enableColors: true }),
    };
}