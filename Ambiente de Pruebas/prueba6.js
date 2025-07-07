import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend } from 'k6/metrics';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

// Métrica personalizada para tiempo de respuesta
let tiempo_respuesta = new Trend('tiempo_respuesta');

export let options = {
    vus: 20,              // Número de usuarios virtuales
    duration: '1m',       // Tiempo total de la prueba
    gracefulStop: '5s',
    thresholds: {
        http_req_duration: ['avg<400'],     // Tiempo promedio < 400ms
        http_req_failed: ['rate<0.01'],     // <1% de errores aceptables
    },
};

export default function () {
    const url = 'http://localhost:8000/api/herramientas?marca=Bosch';
    const res = http.get(url);

    tiempo_respuesta.add(res.timings.duration);

    check(res, {
        '✅ Status 200 OK': (r) => r.status === 200,
        '⏱️ Tiempo < 400ms': (r) => r.timings.duration < 400,
        '❌ Sin errores 500 o 504': (r) => r.status !== 500 && r.status !== 504,
    });

    sleep(1); // para simular espera entre consultas
}

export function handleSummary(data) {
    return {
        'prueba6.json': JSON.stringify(data),
        stdout: textSummary(data, { indent: '→ ', enableColors: true }),
    };
}