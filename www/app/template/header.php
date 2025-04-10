<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Navbar Écologique</title>
  <link rel="stylesheet" href="navbar.css">
</head>
<body>

  <nav class="navbar">
    <div class="navbar-container">
      <div class="navbar-logo">🌍 EcoStat</div>
      <input type="checkbox" id="toggle" class="navbar-toggle">
      <label for="toggle" class="navbar-icon">&#9776;</label>
      <ul class="navbar-menu">
        <li><a href="#">Accueil</a></li>
        <li><a href="#">Statistiques</a></li>
        <li><a href="#">Profil</a></li>
        <li><a href="#">Connexion</a></li>
      </ul>
    </div>
  </nav>

</body>
</html>
<style>
    /* Base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: sans-serif;
}

/* Navbar */
.navbar {
  background-color: #2e7d32; /* vert écolo */
  color: white;
  padding: 0.8rem 1.5rem;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: auto;
}

.navbar-logo {
  font-size: 1.5rem;
  font-weight: bold;
  left:0;
}

.navbar-menu {
  list-style: none;
  display: flex;
  gap: 1.5rem;
}

.navbar-menu li a {
  text-decoration: none;
  color: white;
  transition: color 0.2s;
}

.navbar-menu li a:hover {
  color: #c8e6c9;
}

/* Menu burger */
.navbar-toggle {
  display: none;
}

.navbar-icon {
  font-size: 1.8rem;
  cursor: pointer;
  display: none;
}

/* Responsive */
@media (max-width: 768px) {
  .navbar-icon {
    display: block;
  }

  .navbar-menu {
    display: none;
    flex-direction: column;
    background-color: #388e3c;
    position: absolute;
    top: 60px;
    left: 0;
    width: 100%;
    padding: 1rem 0;
  }

  .navbar-toggle:checked + .navbar-icon + .navbar-menu {
    display: flex;
  }

  .navbar-menu li {
    text-align: center;
    padding: 0.5rem 0;
  }
}

</style>
