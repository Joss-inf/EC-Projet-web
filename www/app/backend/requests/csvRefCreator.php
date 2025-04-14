<?php

class CsvRefCreator
{
    private PDO $db;
    private array $cache = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Fonction générique
    private function createRef(string $table, string $columnName, string $value): int
    {
        // 1. Vérifie le cache
        if (isset($this->cache[$table][$value])) {
            return $this->cache[$table][$value];
        }

        try {
            // 2. Requête d'insertion statique
            $insertQuery = match ($table) {
                'unit' => "INSERT IGNORE INTO unit (name) VALUES (:value)",
                'year' => "INSERT IGNORE INTO year (year) VALUES (:value)",
                'environment' => "INSERT IGNORE INTO environment (name) VALUES (:value)",
                'pollutant' => "INSERT IGNORE INTO pollutant (name) VALUES (:value)",
                'institute_name' => "INSERT IGNORE INTO institute_name (name) VALUES (:value)",
                'postal_code' => "INSERT IGNORE INTO postal_code (postal_code) VALUES (:value)",
                'community' => "INSERT IGNORE INTO community (name) VALUES (:value)",
                'department' => "INSERT IGNORE INTO department (name) VALUES (:value)",
                'region' => "INSERT IGNORE INTO region (name) VALUES (:value)",
                'country' => "INSERT IGNORE INTO country (name) VALUES (:value)",
                'waste' => "INSERT IGNORE INTO waste (name) VALUES (:value)",
                'waste_label' => "INSERT IGNORE INTO waste_label (label) VALUES (:value)",
                default => throw new Exception("Table non supportée : $table")
            };

            $stmt = $this->db->prepare($insertQuery);
            $stmt->execute(['value' => $value]);

            // 3. Récupère l'ID
            $id = (int) $this->db->lastInsertId();

            if ($id === 0) {
                // Si déjà existant, on le récupère
                $selectQuery = match ($table) {
                    'unit' => "SELECT id FROM unit WHERE name = :value",
                    'year' => "SELECT id FROM year WHERE year = :value",
                    'environment' => "SELECT id FROM environment WHERE name = :value",
                    'pollutant' => "SELECT id FROM pollutant WHERE name = :value",
                    'institute_name' => "SELECT id FROM institute_name WHERE name = :value",
                    'postal_code' => "SELECT id FROM postal_code WHERE postal_code = :value",
                    'community' => "SELECT id FROM community WHERE name = :value",
                    'department' => "SELECT id FROM department WHERE name = :value",
                    'region' => "SELECT id FROM region WHERE name = :value",
                    'country' => "SELECT id FROM country WHERE name = :value",
                    'waste' => "SELECT id FROM waste WHERE name = :value",
                    'waste_label' => "SELECT id FROM waste_label WHERE label = :value",
                    default => throw new Exception("Table non supportée pour SELECT : $table")
                };

                $stmt = $this->db->prepare($selectQuery);
                $stmt->execute(['value' => $value]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $row ? (int) $row['id'] : throw new Exception("Échec récupération ID ($table): $value");
            }

            // 4. Stocke dans le cache
            $this->cache[$table][$value] = $id;
            return $id;

        } catch (PDOException | Exception $e) {
            echo "Erreur dans la table '$table' avec la valeur '$value' : " . $e->getMessage();
            return 0;
        }
    }

    // Fonctions spécifiques pour chaque table (lisibles et claires)
    public function createRefForTableUnit(string $value): int
    {
        return $this->createRef('unit', 'name', $value);
    }

    public function createRefForTableYear(string $value): int
    {
        return $this->createRef('year', 'year', $value);
    }

    public function createRefForTableEnvironment(string $value): int
    {
        return $this->createRef('environment', 'name', $value);
    }

    public function createRefForTablePollutant(string $value): int
    {
        return $this->createRef('pollutant', 'name', $value);
    }

    public function createRefForTableInstituteName(string $value): int
    {
        return $this->createRef('institute_name', 'name', $value);
    }

    public function createRefForTablePostalCode(string $value): int
    {
        return $this->createRef('postal_code', 'postal_code', $value);
    }

    public function createRefForTableCommunity(string $value): int
    {
        return $this->createRef('community', 'name', $value);
    }

    public function createRefForTableDepartment(string $value): int
    {
        return $this->createRef('department', 'name', $value);
    }

    public function createRefForTableRegion(string $value): int
    {
        return $this->createRef('region', 'name', $value);
    }

    public function createRefForTableCountry(string $value): int
    {
        return $this->createRef('country', 'name', $value);
    }

    public function createRefForTableWaste(string $value): int
    {
        return $this->createRef('waste', 'name', $value);
    }

    public function createRefForTableWasteLabel(string $value): int
    {
        return $this->createRef('waste_label', 'label', $value);
    }
    public function SetInstitute($institute_name, $postal_code, $community, $department, $region, $siret, $address, $coo_x, $coo_y): void
    {
        // Création des références pour les clés étrangères
        $id_institute_name = $this->CreateRefForTableInstituteName($institute_name);
        $id_postal_code = $this->CreateRefForTablePostalCode($postal_code);
        $id_community = $this->CreateRefForTableCommunity($community);
        $id_department = $this->CreateRefForTableDepartment($department);
        $id_region = $this->CreateRefForTableRegion($region);

        try {
            // Vérifier si le SIRET existe déjà dans la table
            $stmt = $this->db->prepare("SELECT id FROM institute WHERE siret = :siret");
            $stmt->execute(['siret' => $siret]);
            $existingInstitute = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si le SIRET existe déjà, on ne fait rien (skip cette ligne)
            if ($existingInstitute) {
                echo "Institut avec le SIRET $siret déjà existant. Insertion ignorée.\n";
                return;
            }

            // Si pas de doublon, procéder à l'insertion
            $stmt = $this->db->prepare("
            INSERT INTO institute (
                institute_name_id,
                siret,
                address,
                postal_code_id,
                community_id,
                department_id,
                region_id,
                coord_x,
                coord_y
            ) VALUES (
                :institute_name_id,
                :siret,
                :address,
                :postal_code_id,
                :community_id,
                :department_id,
                :region_id,
                :coord_x,
                :coord_y
            )
        ");

            // Exécution de l'insertion
            $stmt->execute([
                'institute_name_id' => $id_institute_name,
                'siret' => $siret,
                'address' => $address,
                'postal_code_id' => $id_postal_code,
                'community_id' => $id_community,
                'department_id' => $id_department,
                'region_id' => $id_region,
                'coord_x' => $coo_x,
                'coord_y' => $coo_y
            ]);
           

        } catch (PDOException $er) {
            echo "Erreur d'insertion dans institute : " . $er->getMessage();
        }
    }
    public function SetwasteProd($institute_name, $waste,$label_waste,$year,$department, $unite,$quantity): void
    {
        $id_institute_name = $this->CreateRefForTableInstituteName($institute_name);
        $id_Department= $this->createRefForTableDepartment(  $department);
        $id_year = $this->createRefForTableYear($year);
        $id_unite = $this->CreateRefForTableUnit($unite);
        $id_waste = $this ->createRefForTableWaste($waste);
        $id_wasteLabel = $this ->createRefForTableWasteLabel($label_waste);

        $stmt = $this->db->prepare("SELECT id FROM institute WHERE siret = :siret");
        $stmt->execute(['siret' => $siret]);
        $existingInstitute = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si le SIRET existe déjà, on ne fait rien (skip cette ligne)
        if ($existingInstitute) {
            echo "Institut avec le SIRET $siret déjà existant. Insertion ignorée.\n";
            return;
        }

        
    }
    public function SetwasteTrait($institute_name, $waste,$label_waste,$year,$department, $unite,$quantity): void
    {
        $id_institute_name = $this->CreateRefForTableInstituteName($institute_name);
        $id_Department= $this->createRefForTableDepartment(  $department);
        $id_year = $this->createRefForTableYear($year);
        $id_unite = $this->CreateRefForTableUnit($unite);
        $id_waste = $this ->createRefForTableWaste($waste);
        $id_wasteLabel = $this ->createRefForTableWasteLabel($label_waste);

    }
}
