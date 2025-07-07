import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend } from 'k6/metrics';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

let tiempo_respuesta_post_stress = new Trend('tiempo_respuesta_post_stress');

export let options = {
    scenarios: {
        stress_test: {
            executor: 'constant-arrival-rate',
            rate: 33,         // ~33 solicitudes por segundo
            duration: '30s',
            timeUnit: '1s',
            preAllocatedVUs: 100,
            maxVUs: 200,
            exec: 'stressPhase',
        },
        recovery_check: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            startTime: '40s',       // espera 10s después del stress
            exec: 'verifyRecovery',
        }
    },
    thresholds: {
        'tiempo_respuesta_post_stress': ['avg<300'], // post stress: tiempo de respuesta < 300ms
        http_req_failed: ['rate<0.01'],
    },
};

const url = 'http://localhost:8000/api/herramientas'; // Cambia a /productos si es distinto

// 🔥 Fase 1: Stress masivo
export function stressPhase() {
    http.get(url);
}

// ✅ Fase 2: Verificación de resiliencia
export function verifyRecovery() {
    let res = http.get(url);

    tiempo_respuesta_post_stress.add(res.timings.duration);

    check(res, {
        '✅ Status 200 OK': (r) => r.status === 200,
        '⏱️ Tiempo < 300ms tras recuperación': (r) => r.timings.duration < 300,
    });
}

export function handleSummary(data) {
    return {
        'prueba7.json': JSON.stringify(data),
        stdout: textSummary(data, { indent: '→ ', enableColors: true }),
    };
}