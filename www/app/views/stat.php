<div class="filter-container">
    <h1>Filtrer les émissions</h1>

    <!-- Barre de recherche -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Rechercher..." class="filter-select">
    </div>
    <div class="filters-row">
        <div class="filter-group">
            <label for="year">Année :</label>
            <select id="year" class="filter-select">
                <option value="">-- Toutes --</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="environment">Environnement :</label>
            <select id="environment" class="filter-select">
                <option value="">-- Tous --</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="pollutant">Polluant :</label>
            <select id="pollutant" class="filter-select">
                <option value="">-- Tous --</option>
            </select>
        </div>
    </div>


    <!-- Tri des résultats -->
    <div class="filter-row">
        <button id="sortAscBtn" class="filter-btn">Trier Croissant</button>
        <button id="sortDescBtn" class="filter-btn">Trier Décroissant</button>
        <button id="filterLoadBtn" class="filter-btn">Charger les Filtres</button>
        <button id="filterBtn" class="filter-btn">Filtrer</button>
    </div>

    <h2>Résultats :</h2>

    <div class="data-filter-container">
        <table class="data-filter">
            <thead>
            </thead>
            <tbody id="results">
            </tbody>
        </table>
    </div>
</div>

