import rooter  from './controller.js'
(() => {
window.navigation = function (direction){
    rooter(direction)
}
    async function register(data) {
        try {
            const response = await fetch("./backend/controllers/register.php", {
                method: "POST", // Spécifier la méthode HTTP
                headers: {
                    "Content-Type": "application/json" // Spécifier le type de contenu
                },
                body: JSON.stringify(data) // Envoyer les données sous forme JSON
            });

            if (!response.ok) { 
                if (response.status === 400) {
                    const res = await response.json();
                    // Afficher l'erreur 400
                    showError(res.message); // Utilise la fonction showError pour afficher le message
                } else {
                    throw new Error(`Erreur HTTP ! Statut : ${response.status}`);
                }
            } else {
                const res = await response.json();
                showRes(res.message || "Inscription réussie !"); // Afficher un message de succès
            }

        } catch (error) {
            console.error("Erreur lors de la requête POST :", error);
        }
    }

    document.getElementById('registerform').addEventListener('submit', async function (event) {
        event.preventDefault();
        const data = {
            name: document.querySelector('[name="nickname"]').value,
            email: document.querySelector('[name="email"]').value,
            confirm_email: document.querySelector('[name="confirm_email"]').value,
            password: document.querySelector('[name="password"]').value,
            confirm_password: document.querySelector('[name="confirm_password"]').value
        };
        try {
            await register(data);
        } catch (error) {
            throw new Error('error: ' + error);
        }
    });

    function showError(message) {
        const errorContainer = document.getElementById('message');
        if (!errorContainer) {
            const newErrorContainer = document.createElement('div');
            newErrorContainer.id = 'message'; // Assurez-vous que l'élément a l'id 'message'
            newErrorContainer.textContent = message;
            newErrorContainer.style.color = 'red'; // Mettre l'erreur en rouge
            document.body.appendChild(newErrorContainer);
        } else {
            errorContainer.textContent = message;
            errorContainer.style.color = 'red';
        }
    }

    function showRes(message) {
        const resContainer = document.getElementById('message');
        if (!resContainer) {
            const newResContainer = document.createElement('div');
            newResContainer.id = 'message'; // Assurez-vous que l'élément a l'id 'message'
            newResContainer.textContent = message;
            newResContainer.style.color = 'green'; // Mettre la réponse en vert
            document.body.appendChild(newResContainer);
        } else {
            resContainer.textContent = message;
            resContainer.style.color = 'green';
        }
    }

})();
