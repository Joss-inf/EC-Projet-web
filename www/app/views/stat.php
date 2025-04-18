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

<script>
    




    const yearSelect = document.getElementById("year");
    const environmentSelect = document.getElementById("environment");
    const pollutantSelect = document.getElementById("pollutant"); // Nouveau filtre
    const resultsTable = document.getElementById("results");
    const filterBtn = document.getElementById("filterBtn");
    const searchInput = document.getElementById("searchInput");
    const sortAscBtn = document.getElementById("sortAscBtn");
    const sortDescBtn = document.getElementById("sortDescBtn");
    const resultsTableHead = document.querySelector('.data-filter thead');
    const dataFilterContainer = document.querySelector('.data-filter-container'); 
    let offset = 0;
    const limit = 10;
    let isLoading = false;
    let currentYear = "";
    let currentEnvironment = "";
    let currentPollutant = "";
    let sortOrder = 'ASC';

    // Charger les filtres dynamiques
    async function loadSelect() {
        try {
            const response = await fetch('./backend/controllers/emissions.php?action=getFilters');
            const data = await response.json();

            data.years.forEach(year => {
                yearSelect.innerHTML += `<option value="${year.year}">${year.year}</option>`;
            });

            data.environments.forEach(env => {
                environmentSelect.innerHTML += `<option value="${env.name}">${env.name}</option>`;
            });

            data.pollutants.forEach(pol => {
                pollutantSelect.innerHTML += `<option value="${pol.name}">${pol.name}</option>`;
            });
        } catch (err) {
            console.error("Erreur loadSelect():", err);
        }
    }

    // Appliquer les filtres
    filterBtn.addEventListener('click', function () {
        offset = 0;
        resultsTable.innerHTML = "";
        currentYear = yearSelect.value;
        currentEnvironment = environmentSelect.value;
        currentPollutant = pollutantSelect.value;
        isLoading = false; 
        loadMoreData();
    });

    // Recherche par mot-clé
    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = resultsTable.querySelectorAll('tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let matches = false;

            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchTerm)) {
                    matches = true;
                }
            });

            row.style.display = matches ? '' : 'none';
        });
    });

    // Boutons de tri
    sortAscBtn.addEventListener('click', function () {
        sortOrder = 'ASC';
        offset = 0;
        resultsTable.innerHTML = "";
        isLoading = false; 
        loadMoreData();
    });

    sortDescBtn.addEventListener('click', function () {
        sortOrder = 'DESC';
        offset = 0;
        resultsTable.innerHTML = "";
        isLoading = false; 
        loadMoreData();
    });

    // Chargement au scroll
    dataFilterContainer.addEventListener("scroll", () => {
        // Vérifier si l'utilisateur est tout en bas du conteneur
        if (dataFilterContainer.scrollHeight - dataFilterContainer.scrollTop <= dataFilterContainer.clientHeight + 100 && !isLoading) {
            isLoading = true;
            loadMoreData();
        }
    });

    function loadMoreData() {
        isLoading = true;
    
        const query = new URLSearchParams({
            action: 'getData',
            year: currentYear,
            environment: currentEnvironment,
            pollutant: currentPollutant,
            offset,
            limit,
            sortOrder
        });
        fetch(`./backend/controllers/emissions.php?${query}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) return;

                renderDynamicTableHeader(data, resultsTableHead);
                data.forEach(row => {
                    resultsTable.innerHTML += `
                        <tr>
                            <td>${row.institute_name}</td>
                            <td>${row.year}</td>
                            <td>${row.env}</td>
                            <td>${row.pollutant}</td>
                            <td>${row.quantity}</td>
                            <td>${row.unit}</td>
                        </tr>
                    `;
                });

                offset += data.length;
                isLoading = false;
            })
            .catch(err => {
                console.error("Erreur fetch loadMoreData:", err);
                isLoading = false;
            });
    }

    // Génère le head dynamique
    function renderDynamicTableHeader(data, target) {
        if (!data || data.length === 0) return;

        const list = ['<tr>'];
        if (data.some(row => row.institute_name)) list.push('<th>Entreprise</th>');
        if (data.some(row => row.year)) list.push('<th>Année</th>');
        if (data.some(row => row.env)) list.push('<th>Environnement</th>');
        if (data.some(row => row.pollutant)) list.push('<th>Polluant</th>');
        if (data.some(row => row.quantity)) list.push('<th>Quantité</th>');
        if (data.some(row => row.unit)) list.push('<th>Unité</th>');
        list.push('</tr>');

        target.innerHTML = list.join('');
    }

    // Initialisation
    loadSelect();
    loadMoreData();
</script>