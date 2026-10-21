/** Cliente HTTP da API Vitalize. As sessões usam cookie HttpOnly e CSRF. */
(() => {
    'use strict';
    let csrfToken = '';
    const apiBase = new URL('api/', document.baseURI).pathname.replace(/\/$/, '');
    const urlApi = caminho => caminho.startsWith('/api/') ? `${apiBase}${caminho.slice(4)}` : caminho;

    async function tokenCsrf() {
        if (csrfToken) return csrfToken;
        const resposta = await fetch(urlApi('/api/csrf'), { credentials: 'same-origin', headers: { Accept: 'application/json' } });
        const dados = await resposta.json();
        if (!resposta.ok || !dados.csrf_token) throw new Error('Não foi possível iniciar uma sessão segura.');
        csrfToken = dados.csrf_token;
        return csrfToken;
    }

    async function requisicaoApi(caminho, opcoes = {}) {
        const metodo = String(opcoes.method || 'GET').toUpperCase();
        const headers = new Headers(opcoes.headers || {});
        headers.set('Accept', 'application/json');
        const corpo = opcoes.body;
        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(metodo)) headers.set('X-CSRF-Token', await tokenCsrf());
        if (corpo && !(corpo instanceof FormData) && typeof corpo !== 'string') {
            headers.set('Content-Type', 'application/json');
            opcoes.body = JSON.stringify(corpo);
        }
        const resposta = await fetch(urlApi(caminho), { ...opcoes, method: metodo, headers, credentials: 'same-origin' });
        let dados;
        try { dados = await resposta.json(); } catch { throw new Error('A API devolveu uma resposta inválida.'); }
        if (!resposta.ok) throw new Error(dados.erro || 'Não foi possível concluir a solicitação.');
        if (caminho.includes('/auth/login') || caminho.includes('/auth/register') || caminho.includes('/auth/logout')) csrfToken = '';
        return dados;
    }

    window.VitalizeAPI = { requisicao: requisicaoApi };
})();
