import navbar from  './navbar.js'
export default async function rooter(page) {

    if (typeof page !== "string") return alert("don't play with the rooter please");
    const container = document.getElementById('page-content');
    const loader = document.getElementById('global-loader');
    if (page === 'logout') {
        page = 'home'
        try {
            const response = await fetch('./backend/controllers/disconnexion.php?action=userLeft');
            if (!response.ok) {
                throw new Error(`Erreur HTTP ! Statut : ${response.status}`);
            }

            const data = await response.text();
            console.log(data)
            if (!response.ok) { throw new Error(`Erreur HTTP ! Statut : ${response.status}`); }
            if (data["status"] == 200) {
                
            }
        } catch (err) {
            console.error("Erreur updateNavbar():", err);
        }
        navbar()
    }
    container.classList.add('loading');
    loader.classList.add('active');
    fetch(`./backend/controllers/rooter.php?page=${page}`, {
        method: 'GET'
    })
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.text();
        })
        .then(async html => {
            await loadHTML(html, container);
            try {
                await loadCSS(page);
                setTimeout(() => {
                    container.classList.remove('loading');
                    loader.classList.remove('active');
                }, 0);
            } catch (error) {
                console.error('Erreur lors du chargement du CSS', error);
            }
        })
        
        .then(() => {
            setTimeout(() => {
                loadJS(page)
            }, 1000);
        }      // Charger le script home.js de manière dynamique
    )
}


function loadHTML(html, container) {
    return new Promise(resolve => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const bodyContent = doc.body;
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
        Array.from(bodyContent.childNodes).forEach(node => {
            container.appendChild(node.cloneNode(true));
        });
        resolve();

    });
}

function loadCSS(page) {
    return new Promise((resolve, reject) => {
        const oldLink = document.querySelector('link[data-page-style]');
        if (oldLink) oldLink.remove();
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = `./styles/${page}.css`;
        link.setAttribute('data-page-style', '');
        link.onload = () => resolve();
        link.onerror = () => reject(new Error(`Échec du chargement du CSS de la page ${page}`));
        document.head.appendChild(link);
    });
}

function loadJS(page) {
    return new Promise((resolve, reject) => {
        // Vérifier si le script est déjà présent avec le bon src et data-page-script
        const existingScript = document.querySelector(`script[data-page-script]`);
        if (existingScript) {
            existingScript.remove();  // Retirer le script précédent
            console.log(`Script ${page}.js supprimé et va être rechargé.`);
        }
   
        // Créer un nouvel élément <script> pour charger le fichier JS
    
        
        // Ajouter un paramètre unique pour éviter le cache
        const uniqueParam = `?v=${new Date().getTime()}`; // Timestamp comme version unique
        const script = document.createElement('script');
        // Définir la source du script
        script.src = `./components/${page}.js${uniqueParam}`;
        script.setAttribute('data-page-script', page); // Ajouter un attribut pour identifier le script
        script.defer = true; // Charger le script de manière asynchrone, en différé
        script.async = true; // Charger le script en mode asynchrone
        script.type = 'module'; // Charger le fichier en tant que module ES6

        // Résolution de la promesse lorsque le script est chargé avec succès
        script.onload = () => {
            console.log(`${page}.js a été chargé avec succès.`);
            if (window.initializeStatPage) {
                window.initializeStatPage(); // Explicitly call the initialization function
            }
            resolve();
        };

        // Rejet de la promesse en cas d'erreur de chargement
        script.onerror = (error) => {
            console.error(`Erreur lors du chargement du script ${page}.js :`, error);
            reject(new Error(`Erreur de chargement du script ${page}.js`)); // Rejet de la promesse en cas d'erreur
        };

        // Ajouter le script au body du document pour le charger
        document.body.appendChild(script);
    });
}

