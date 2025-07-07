import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend } from 'k6/metrics';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

let tiempo_respuesta = new Trend('tiempo_respuesta');

export let options = {
    vus: 10,             // 10 usuarios virtuales
    duration: '1m',      // durante 1 minuto
    gracefulStop: '10s',
    thresholds: {
        http_req_duration: ['p(95)<1000'], // el 95% de las respuestas deben estar bajo 1s
        http_req_failed: ['rate<0.01'],    // menos del 1% de errores permitidos
    },
};

export default function () {
    let res = http.get('http://localhost:8000/api/herramientas/1');

    tiempo_respuesta.add(res.timings.duration);

    check(res, {
        '✅ Status 200 OK': (r) => r.status === 200,
        '⏱️ Tiempo < 1s': (r) => r.timings.duration < 1000,
        '📦 Contiene campo manual': (r) => r.json().manual !== undefined,
    });

    sleep(1); // controla ritmo entre solicitudes
}

export function handleSummary(data) {
    return {
        'prueba3.json': JSON.stringify(data),
        stdout: textSummary(data, { indent: '→ ', enableColors: true }),
    };
}