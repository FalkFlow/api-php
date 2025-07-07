import http from 'k6/http';
import { check } from 'k6';

export let options = {
    vus: 1,
    iterations: 1,
};

export default function () {
    const res = http.get('http://localhost:8000/api/herramientas');
    check(res, {
        '✅ Respuesta 200': (r) => r.status === 200,
        '📦 Contenido recibido': (r) => r.body && r.body.includes("codigo_producto"),
    });
}
