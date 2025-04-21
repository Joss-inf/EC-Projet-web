<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="./assets/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/icon/favicon-16x16.png">
    <link rel="manifest" href="./assets/icon/site.webmanifest">
    <meta name="description" content="Ecostat vous permet de consulter des statistiques détaillées
  sur la pollution générée par les produits industriels. Découvrez les impacts environnementaux
  des produits dangereux et non dangereux, année après année.">
    <meta name="keywords"
        content="pollution, entreprises, données, écologie, produits industriels, impact environnemental,air,eau,sol,statistiques,pollution de l'air,pollution de l'eau,pollution des sols">
    <meta name="author" content="Ecostat Team">
    <title>Ecostat</title>
    <link rel="stylesheet" href="./styles/index.css">
    <script type="module" async src="./components/index.js"></script>
</head>

<body>
    <?php require("." . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR . "header.php"); ?>
    <div id="global-loader"></div>
    <main id="page-content"></main>
    <?php require("." . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR . "footer.php"); ?>
</body>

</html>
<script>
    function scrollToSection(sectionId) {
        const section = document.getElementById(sectionId)
        section.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
</script>