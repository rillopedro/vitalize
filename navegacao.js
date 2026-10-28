(() => {
    'use strict';
    const config = JSON.parse(document.getElementById('vitalize-config').textContent);
    const quadro = document.getElementById('vitalize-pagina');
    const raiz = new URL(config.base, location.origin);
    const chave = 'vitalize_pagina:' + raiz.pathname;
    const urlValida = valor => {
        try {
            const url = new URL(valor, raiz);
            return url.origin === raiz.origin && url.pathname === raiz.pathname + 'conteudo.php'
                && config.paginas.includes(url.searchParams.get('pagina')) ? url : null;
        } catch { return null; }
    };
    let destino = config.inicial;
    // Uma atualização restaura a tela atual apenas nesta aba.
    if (location.pathname === raiz.pathname && !location.search) {
        try { destino = urlValida(sessionStorage.getItem(chave))?.href || destino; } catch {}
    }
    history.replaceState(null, '', raiz.pathname);
    quadro.addEventListener('load', () => {
        try {
            const url = urlValida(quadro.contentWindow.location.href);
            if (!url) return;
            sessionStorage.setItem(chave, url.href);
            document.title = quadro.contentDocument.title || 'Vitalize';
            for (const link of quadro.contentDocument.querySelectorAll('a[href]')) {
                const destino = new URL(link.href, url);
                if (destino.origin !== raiz.origin && ['http:', 'https:'].includes(destino.protocol)) {
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                }
            }
        } catch { /* Armazenamento indisponível não impede a navegação. */ }
    });
    quadro.src = destino;
})();
