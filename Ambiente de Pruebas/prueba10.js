import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend } from 'k6/metrics';

let tiempo_keep_alive = new Trend('tiempo_keep_alive');

export const options = {
  vus: 5,             // 5 usuarios virtuales concurrentes
  iterations: 50,     // total 50 requests
};

export default function () {
  const url = 'http://host.docker.internal:8000/api/herramientas';

  // Header con keep-alive explícito
  const params = {
    headers: {
      'Connection': 'keep-alive',
    },
  };

  const start = Date.now();
  const res = http.get(url, params);
  const duration = Date.now() - start;

  tiempo_keep_alive.add(duration);

  check(res, {
    'status 200': (r) => r.status === 200,
  });

  sleep(0.1); // pequeña pausa para simular comportamiento real
}

// Opcional: función para exportar resultados en consola
export function handleSummary(data) {
  return {
    stdout: `\n→ Tiempo medio con keep-alive: ${data.metrics.tiempo_keep_alive.avg.toFixed(2)} ms\n`,
  };
}
