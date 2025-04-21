import rooter  from './controller.js'
import updateNavbar from  './navbar.js'


window.navigation = function (direction){
    rooter(direction)
}
async function login(data) {
    try {
        const response = await fetch("./backend/controllers/login.php", {
            method: "POST", // Spécifier la méthode HTTP
            headers: {
                "Content-Type": "application/json" // Spécifier le type de contenu
            },
            body: JSON.stringify(data) // Envoyer les données sous forme JSON
        });
        if (!response.ok) { throw new Error(`Erreur HTTP ! Statut : ${response.status}`); }
        const res = await response.json();
        const container = document.getElementById('response-container');
        container.innerHTML = ''; // Nettoyer le précédent message

        const alertDiv = document.createElement('div');
        alertDiv.classList.add('alert');
        alertDiv.classList.add(res['status'] === 200 ? 'alert-success' : 'alert-error');
        const strong = document.createElement('strong');
        const message = document.createElement('span');
        message.textContent = res['message']+' connected';
        alertDiv.appendChild(strong);
        alertDiv.appendChild(message );
        container.appendChild(alertDiv);
        if(res['status'] === 200){
            
            updateNavbar()
            rooter('home')
            
        }
    } catch (error) {
        console.error("Erreur lors de la requête POST :", error);
    }
}

document.getElementById('loginform').addEventListener('submit', async function (event) {
    event.preventDefault();
    const data = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    try {
        await login(data);
    } catch (error) {
        console.error(error);
    }
});


