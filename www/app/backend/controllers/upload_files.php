<?php
session_start();
require_once '../requests/csvRefCreator.php';
require_once '../requests/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvfiles'])) {
    $req = new CsvRefCreator(Database::getConnection());

    $files = $_FILES['csvfiles'];
    for ($i = 0; $i < count($files['name']); $i++) {
        $fileTmpName = $files['tmp_name'][$i];
        $fileName = $files['name'][$i];
        $fileError = $files['error'][$i];
        if ($fileError === 0) {
            echo "Traitement du fichier : $fileName<br>";
            if (($handle = fopen($fileTmpName, "r")) !== false) {
                $firstRow = true;
                while (($row = fgetcsv($handle, 1000, ";")) !== false) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    }
                    // Exemple de logique conditionnelle selon le nom du fichier
                    if ($fileName === 'etablissements.csv') {
                        $name_entity = $row[1];
                        $siret_entity = $row[2];
                        $address = $row[3];
                        $postal_code = $row[4];
                        $commune = $row[5];
                        $department = $row[6];
                        $region = $row[7];
                        $CX = $row[8];
                        $CY = $row[9];
                        $req->SetInstitute($name_entity, $postal_code, $commune, $department, $region, $siret_entity, $address, $CX, $CY);
                    } elseif ($fileName === 'emissions.csv') {
                        $name_entite = $row[1];
                        $year = $row[2];
                        $environment = $row[3];
                        $waste = $row[4];
                        $quantity = $row[5];
                        $unite = $row[6];
                    } elseif ($fileName === 'Prod_dechets_dangereux.csv' || $fileName === 'Prod_dechets_non_dangereux.csv') {
                        $country = $row[7];
                        if ($country){
                            continue;
                        }
                        $institute_name = $row[1];
                        $waste = $row[2];
                        $year = $row[3];
                        $department = $row[6];
                        $label_waste = $row[9];
                        $quantity = $row[10];
                        $unite = $row[11];
                        $req->SetwasteProd($institute_name, $waste, $label_waste,  $year,  $department, $unite, $quantity)
                    } elseif ($fileName === 'Trait_dechets_dangereux.csv' || $fileName === 'Trait_dechets_non_dangereux.csv') {
                        $country = $row[7];
                        if ($country){
                            continue;
                        }
                        $institute_name = $row[1];
                        $pollutant = $row[2];
                        $year = $row[3];
                        $department = $row[6];
                        $libel_waste = $row[9];
                        $quantity_in = $row[10];
                        $quantity_out = $row[11];
                        $unite = $row[12];
                    } else {
                        echo "Fichier non reconnu : $fileName<br>";
                    }
                }

                fclose($handle);
                echo "Fichier $fileName traité avec succès.<br>";
            } else {
                echo "Erreur lors de l'ouverture du fichier $fileName<br>";
            }
        } else {
            echo "Erreur avec le fichier $fileName<br>";
        }
    }
} else {
    echo "Aucun fichier n'a été envoyé.";
}
?>