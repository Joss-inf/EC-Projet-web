<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - ÉcoStat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
  <div class="login-container">
    <img src="   https://cdn-icons-png.flaticon.com/512/2640/2640454.png " alt="Écologie">

    <h1>Connexion ÉcoStat</h1>

    <form action="traitement_login.php" method="post">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Votre email" required>

      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>

      <button type="submit">Se connecter</button>
    </form>

    <?php if (isset($_GET['error'])): ?>
      <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <p class="footer">
      Pas encore inscrit ? <a href="register.php">Créer un compte</a>
    </p>
  </div>
</body>
</html>
