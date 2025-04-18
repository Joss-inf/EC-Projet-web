<?php
require_once '../requests/emissions.php';
require_once '../requests/database.php';

header('Content-Type: application/json');

$model = new Emissions(Database::getConnection());

if (isset($_GET['action']) && $_GET['action'] === 'getFilters') {
    http_response_code(response_code: 200);
    echo json_encode(value: [
        "years" => $model->getAllYears(),
        "environments" => $model->getAllEnvironments(),
        "pollutants" => $model->getAllPollutants() 
    ]);
    exit;
}

// Récupération des données filtrées
if (isset($_GET['action']) && $_GET['action'] === 'getData') {
    $year = $_GET['year'] ?? null;
    $environment = $_GET['environment'] ?? null;
    $pollutant = $_GET['pollutant'] ?? null; 
    $instituteName = $_GET['institute'] ?? null;
    $sortOrder = strtoupper($_GET['sortOrder'] ?? 'ASC');
    $sortOrder = in_array($sortOrder, ['ASC', 'DESC']) ? $sortOrder : 'ASC';

    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    $data = $model->searchEmissions($year, $environment, $pollutant, $instituteName, $limit, $offset, $sortOrder); // ✅ MAJ

    echo json_encode($data);
    exit;
}
