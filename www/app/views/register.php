<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription - ÉcoStat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/register.css">
</head>
<body>
  <div class="register-container">
    <img src="https://cdn-icons-png.flaticon.com/512/2640/2640454.png " alt="Écologie">
    <h1>Créer un compte</h1>

    <form action="traitement_register.php" method="post">
      <label for="name">Nom</label>
      <input type="text" id="name" name="name" placeholder="Votre nom" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Votre email" required>

      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" placeholder="Mot de passe" required>

      <label for="confirm_password">Confirmer le mot de passe</label>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez le mot de passe" required>

      <button type="submit">S'inscrire</button>
    </form>

    <?php if (isset($_GET['error'])): ?>
      <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php elseif (isset($_GET['success'])): ?>
      <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
    <?php endif; ?>

    <p class="footer">
      Déjà inscrit ? <a href="login.php">Se connecter</a>
    </p>
  </div>
</body>
</html>
