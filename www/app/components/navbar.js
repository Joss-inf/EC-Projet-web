export default async function updateNavbar() {
    try {
        const response = await fetch('./backend/controllers/session.php?action=getSession');
        if (!response.ok) {
            throw new Error(`Erreur HTTP ! Statut : ${response.status}`);
        }

        const data = await response.json(); 
        const role = data.message; 
        const navbarMenu = document.querySelector('.navbar-menu');
        if (!navbarMenu) return;

        // Base commune à tout le monde
        navbarMenu.innerHTML = ""
        let html = `
            <li><a class="navigation" onclick="navigation('home')">Accueil</a></li>
            <li><a class="navigation" onclick="navigation('stat')">Statistiques</a></li>

        `;

        if (role === 'guest') {
            html += `
                <li><a class="navigation" onclick="navigation('login')">Connexion</a></li>
            `;
        } else if (role === 'user') {
            html += `
                <li><a class="navigation" onclick="navigation('user_profile')">Profil</a></li>
                <li><a class="navigation" onclick="navigation('logout')">🔓</a></li>
            `;
        } else if (role === 'admin') {
            html += `
                <li><a class="navigation" onclick="navigation('user_profile')">Profil</a></li>
                <li><a class="navigation" onclick="navigation('admin_dashboard')">Administration</a></li>
                <li><a class="navigation" onclick="navigation('logout')">🔓</a></li>
            `;
        }

        navbarMenu.innerHTML = html;

    } catch (err) {
        console.error("Erreur updateNavbar():", err);
    }
}
