import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  vus: 10,
  duration: '10s',
  thresholds: {
    http_req_duration: ['p(95)<500']
  }
};

export default function () {
  const url = 'http://localhost:8000/api/empleado/login';
  const payload = JSON.stringify({
    correo: 'prueba@user.cl',
    password: '123456'
  });

  const params = {
    headers: { 'Content-Type': 'application/json' }
  };

  const res = http.post(url, payload, params);

  check(res, {
    'status es 200': (r) => r.status === 200,
    'tiempo < 500ms': (r) => r.timings.duration < 500
  });

  sleep(1);
}