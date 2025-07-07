import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend } from 'k6/metrics';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

// Métricas personalizadas
let tiempo_login = new Trend('tiempo_login');
let tiempo_consulta = new Trend('tiempo_consulta');

export const options = {
  scenarios: {
    default: {
      executor: 'shared-iterations',
      vus: 1,
      iterations: 1,
      maxDuration: '10m',
      gracefulStop: '30s',
    },
  },
};

export default function () {
  const loginPayload = JSON.stringify({
    email: 'prueba@user.cl',
    password: 'Prueba1234',
  });

  const headers = { 'Content-Type': 'application/json' };

  // Login
  const loginStart = Date.now();
  const resLogin = http.post('http://host.docker.internal:8000/api/login', loginPayload, { headers });
  const loginTime = Date.now() - loginStart;
  tiempo_login.add(loginTime);

  check(resLogin, {
    '✅ Login status 200': (r) => r.status === 200,
    '🔐 Login exitoso': (r) => r.body && r.body.includes('token'),
  });

  sleep(1);

  // Consulta
  const consultaStart = Date.now();
  const resConsulta = http.get('http://host.docker.internal:8000/api/herramientas');
  const consultaTime = Date.now() - consultaStart;
  tiempo_consulta.add(consultaTime);

  check(resConsulta, {
    '✅ Consulta status 200': (r) => r.status === 200,
    '📦 Contenido recibido': (r) => r.body && r.body.length > 0,
  });
}

// Función para guardar el resumen en JSON y mostrar resumen coloreado en consola
export function handleSummary(data) {
  return {
    'resultado_prueba9.json': JSON.stringify(data),
    stdout: textSummary(data, { indent: '→ ', enableColors: true }),
  };
}
