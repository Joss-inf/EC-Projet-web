// Charger les données utilisateur au chargement de la page
async function loadUser() {
    try {
        const response = await fetch('./backend/controllers/user_profile.php?action=getUser');
        if (!response.ok) throw new Error(`Erreur HTTP ! Statut : ${response.status}`);
        const data = await response.json();

        // Afficher les informations utilisateur
        document.getElementById('display-pseudo').textContent = data['message'].username;
        document.getElementById('pseudo').value = data['message'].username;
        document.getElementById('email').value = data['message'].email;
    } catch (error) {
        console.error("Erreur lors de la requête GET :", error);
    }
}

// Soumettre toutes les informations (profil et mot de passe) dans un seul POST
async function updateProfile(event) {
    event.preventDefault(); // Empêcher la soumission du formulaire par défaut

    const email = document.getElementById('email').value;
    const pseudo = document.getElementById('pseudo').value;
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Vérification des mots de passe
    if (newPassword !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas.');
        return;
    }

    // Construction de l'objet de données à envoyer
    const formData = {
        action: 'update_profile',
        email: email,
        pseudo: pseudo,
        current_password: currentPassword,
        new_password: newPassword,
        confirm_password:confirmPassword
    };

    try {
        const response = await fetch('./backend/controllers/user_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Indiquer que nous envoyons du JSON
            },
            body: JSON.stringify(formData) // Convertir l'objet en JSON pour l'envoyer
        });

        const data = await response.json();
        // Vérifier la réponse et informer l'utilisateur
        if (data["status"] == 200) {
            alert('Profil et mot de passe mis à jour avec succès.');
            document.getElementById('display-pseudo').textContent = pseudo; // Mettre à jour le pseudo affiché
        } else {
            alert('Erreur : ' + data['message']);
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour du profil et du mot de passe :', error);
    }
}

document.getElementById('getprofil').addEventListener('click', function(event) {

    event.preventDefault(); 

    loadUser();
});
 
    (() => {
        loadUser();
    })();
 
    // Ajouter l'événement de soumission pour la mise à jour du profil et du mot de passe
    document.getElementById('update-profile-form').addEventListener('submit', updateProfile);
