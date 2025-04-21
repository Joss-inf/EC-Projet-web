<div class="error-container">
    <h1>Erreur <?= $code ?? '???' ?></h1>
    <p><?= $message ?? "Une erreur est survenue." ?></p>
    <a href="#" onclick="window.history.back()">Retour</a>
</div>
<style>
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(to top, #d0f0c0, #218d6c);
        font-family: sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: white;
    }

    .error-container {
        background: rgba(0, 0, 0, 0.2);
        padding: 2rem 3rem;
        border-radius: 12px;
        text-align: center;
    }

    .error-container h1 {
        margin-bottom: 1rem;
        font-size: 2.5rem;
    }

    .error-container p {
        font-size: 1.2rem;
    }

    .error-container a {
        color: white;
        text-decoration: underline;
        margin-top: 1rem;
        display: inline-block;
    }
</style>