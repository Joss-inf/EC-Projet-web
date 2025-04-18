<div class="wrapper-container">
  <div class="form-wrapper">
    <div class="form-box">
      <img src="https://cdn-icons-png.flaticon.com/512/2640/2640454.png" alt="Ecostat" class="logo" />
      <h1>Créer un compte</h1>
      <div id="message"></div>
      <form id="registerform" method="post">
        <input type="text" name="nickname" placeholder="Nom" required />
        <input type="email" name="email" placeholder="Adresse e-mail" required />
        <input type="email" name="confirm_email" placeholder="Confirmer l'email" required />
        <input type="password" name="password" placeholder="Mot de passe" required />
        <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required />
        <button type="submit">S'inscrire</button>
      </form>
      <p class="footer-text">Déjà inscrit ? <a href="#" onclick="navigation('login')">Se connecter</a></p>
    </div>
  </div>
</div>