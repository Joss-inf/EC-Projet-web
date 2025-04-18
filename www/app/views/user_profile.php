<div class="profile-wrapper">
  <div class="profile-container">
    <h1>Profil de <span id="display-pseudo">...</span></h1>
    <button id = "getprofil" >afficher  profile</button>
    <!-- Formulaire de mise à jour du profil et de changement de mot de passe -->
    <form id="update-profile-form" method="POST">
      <input type="hidden" name="action" value="update_profile">

      <!-- Mise à jour de l'email et du pseudonyme -->
      <div class="profile-section">
        <h2>Mise à jour du profil</h2>

        <label for="email">Nouvel Email :</label>
        <input type="email" name="email" id="email" required>

        <label for="pseudo">Nouveau Pseudonyme :</label>
        <input type="text" name="pseudo" id="pseudo" required>
      </div>

      <!-- Changement de mot de passe -->
      <h4>Mot de passe actuel requis pour toute modification</h4>
      <div class="profile-section">
        <h2>Changer le mot de passe</h2>

        <label for="current_password">Mot de passe actuel :</label>
        <input type="password" name="current_password" id="current_password" required>

        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" name="new_password" id="new_password" >

        <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
        <input type="password" name="confirm_password" id="confirm_password" >
      </div>

      <!-- Bouton de soumission pour tout -->
      <button type="submit">Mettre à jour</button>
    </form>
  </div>
</div>
