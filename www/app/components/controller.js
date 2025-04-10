function rooter(page) {
    if (typeof page !== "string") return alert('don\'t play with the rooter please')
    // Imaginons que buttonId vaut "admin_dashboard"
    fetch(`./backend/controllers/rooter.php?page=${page}`, {
        method: 'GET'
    })
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const bodyContent = doc.body;

            // Replace current body with the loaded one, without using innerHTML
            const container = document.getElementById('page-content');
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }

            // Clone and append all child nodes safely
            Array.from(bodyContent.childNodes).forEach(node => {
                container.appendChild(node.cloneNode(true));
            });
        })
    .then( loadCSS(page))
   .then(loadJS(page))
        .catch(error => {
            console.error('Erreur :', error);
        });
}
function loadCSS(page) {
        const oldLink = document.querySelector('link[data-page-style]');
        if (oldLink) oldLink.remove();
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = `../../styles/${page}.css`;
    link.setAttribute('data-page-style', '');
    document.head.appendChild(link);
}

function loadJS(page) {
    const oldScript = document.querySelector('script[data-page-script]');
    if (oldScript) oldScript.remove();

    const script = document.createElement('script');
    script.src = `./${page}.js`;
    script.setAttribute('data-page-script', '');
    script.defer = true;
    document.body.appendChild(script);
}
