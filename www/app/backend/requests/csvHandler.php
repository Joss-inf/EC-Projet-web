<?php

class CsvHandler
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
    private array $refCache = [];

    private function getCachedRef(string $type, string $value): int
    {
        if (!isset($this->refCache[$type][$value])) {
            switch ($type) {
                case 'institute_name':
                    $id = $this->CreateRefForTableInstituteName($value);
                    break;
                case 'department':
                    $id = $this->createRefForTableDepartment($value);
                    break;
                case 'year':
                    $id = $this->createRefForTableYear($value);
                    break;
                case 'unit':
                    $id = $this->CreateRefForTableUnit($value);
                    break;
                case 'waste':
                    $id = $this->createRefForTableWaste($value);
                    break;
                case 'waste_label':
                    $id = $this->createRefForTableWasteLabel($value);
                    break;
                case 'environment':
                    $id = $this->createRefForTableEnvironment($value);
                    break;
                case 'pollutant':
                    $id = $this->createRefForTablePollutant($value);
                    break;
                default:
                    throw new Exception("Type de référence inconnu : $type");
            }
            $this->refCache[$type][$value] = $id;
        }

        return $this->refCache[$type][$value];
    }

    public function SetwasteProd($institute_name, $waste, $label_waste, $year, $department, $unite, $quantity): void
    {
        set_time_limit(300); // temporaire

        $id_institute_name = $this->getCachedRef('institute_name', $institute_name);
        $id_department = $this->getCachedRef('department', $department);
        $id_year = $this->getCachedRef('year', $year);
        $id_unit = $this->getCachedRef('unit', $unite);
        $id_waste = $this->getCachedRef('waste', $waste);
        $id_waste_label = $this->getCachedRef('waste_label', $label_waste);

        try {
            $stmt = $this->db->prepare("
            SELECT id, prod_quantity
            FROM waste_production
            WHERE institute_name_id = :institute_name_id
              AND year_id = :year_id
              AND department_id = :department_id
              AND waste_id = :waste_id
              AND waste_label_id = :waste_label_id
        ");
            $stmt->execute([
                'institute_name_id' => $id_institute_name,
                'year_id' => $id_year,
                'department_id' => $id_department,
                'waste_id' => $id_waste,
                'waste_label_id' => $id_waste_label
            ]);

            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                $insert = $this->db->prepare("
                INSERT INTO waste_production (
                    institute_name_id, department_id, year_id, unit_id,
                    waste_id, waste_label_id, prod_quantity
                ) VALUES (
                    :institute_name_id, :department_id, :year_id, :unit_id,
                    :waste_id, :waste_label_id, :prod_quantity
                )
            ");
                $insert->execute([
                    'institute_name_id' => $id_institute_name,
                    'department_id' => $id_department,
                    'year_id' => $id_year,
                    'unit_id' => $id_unit,
                    'waste_id' => $id_waste,
                    'waste_label_id' => $id_waste_label,
                    'prod_quantity' => $quantity
                ]);

            } elseif (
                is_null($existing['prod_quantity'])
            ) {
                $update = $this->db->prepare("
                UPDATE waste_production
                SET prod_quantity = :prod_quantity
                WHERE id = :id
            ");
                $update->execute([
                    'prod_quantity' => $quantity,
                    'id' => $existing['id']
                ]);
            } else {
                // echo " Aucune insertion ni mise à jour pour $institute_name ($year - $department)\n";
            }

        } catch (PDOException $e) {
            echo "Erreur dans SetwasteProd : " . $e->getMessage();
        }
    }
    public function SetwasteTrait($institute_name, $waste, $label_waste, $year, $department, $unite, $quantity_in, $quantity_treated): void
    {
        set_time_limit(300); // temporaire

        $id_institute_name = $this->getCachedRef('institute_name', $institute_name);
        $id_department = $this->getCachedRef('department', $department);
        $id_year = $this->getCachedRef('year', $year);
        $id_unit = $this->getCachedRef('unit', $unite);
        $id_waste = $this->getCachedRef('waste', $waste);
        $id_waste_label = $this->getCachedRef('waste_label', $label_waste);

        try {
            $stmt = $this->db->prepare("
            SELECT id, quantity_in, quantity_treated
            FROM waste_production
            WHERE institute_name_id = :institute_name_id
              AND year_id = :year_id
              AND department_id = :department_id
              AND waste_id = :waste_id
              AND waste_label_id = :waste_label_id
        ");
            $stmt->execute([
                'institute_name_id' => $id_institute_name,
                'year_id' => $id_year,
                'department_id' => $id_department,
                'waste_id' => $id_waste,
                'waste_label_id' => $id_waste_label
            ]);

            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                $insert = $this->db->prepare("
                INSERT INTO waste_production (
                    institute_name_id, department_id, year_id, unit_id,
                    waste_id, waste_label_id, quantity_in, quantity_treated
                ) VALUES (
                    :institute_name_id, :department_id, :year_id, :unit_id,
                    :waste_id, :waste_label_id, :quantity_in, :quantity_treated
                )
            ");
                $insert->execute([
                    'institute_name_id' => $id_institute_name,
                    'department_id' => $id_department,
                    'year_id' => $id_year,
                    'unit_id' => $id_unit,
                    'waste_id' => $id_waste,
                    'waste_label_id' => $id_waste_label,
                    'quantity_in' => $quantity_in,
                    'quantity_treated' => $quantity_treated
                ]);

            } elseif (
                is_null($existing['quantity_treated']) &&
                is_null($existing['prod_quantity'])
            ) {
                $update = $this->db->prepare("
                UPDATE waste_production
                SET quantity_in = :quantity_in,
                    quantity_treated = :quantity_treated
                WHERE id = :id
            ");
                $update->execute([
                    'quantity_in' => $quantity_in,
                    'quantity_treated' => $quantity_treated,
                    'id' => $existing['id']
                ]);
            } else {
                // echo " Aucune insertion ni mise à jour pour $institute_name ($year - $department)\n";
            }

        } catch (PDOException $e) {
            echo "Erreur dans SetwasteTrait : " . $e->getMessage();
        }
    }
    public function SetEmission($institute_name, $year, $environment, $waste, $quantity, $unite): void
    {
        set_time_limit(300); // temporaire

        $id_institute_name = $this->getCachedRef('institute_name', $institute_name);
        $id_environment = $this->getCachedRef('environment', $environment); // on le met dans le cache
        $id_year = $this->getCachedRef('year', $year);
        $id_unit = $this->getCachedRef('unit', $unite);
        $id_waste_label = $this->getCachedRef('pollutant', $waste); // aliasé ici
        try {
            // Vérifier si une ligne identique existe déjà
            $check = $this->db->prepare("
            SELECT COUNT(*) FROM emission
            WHERE institute_name_id = :institute_name_id
              AND year_id = :year_id
              AND environment_id = :environment_id
              AND pollutant_id = :pollutant_id
              AND unit_id = :unit_id
              AND quantity = :quantity
        ");

            $check->execute([
                'institute_name_id' => $id_institute_name,
                'year_id' => $id_year,
                'environment_id' => $id_environment,
                'pollutant_id' => $id_waste_label,
                'unit_id' => $id_unit,
                'quantity' => $quantity
            ]);

            $exists = $check->fetchColumn();

            if ($exists == 0) {
                $insert = $this->db->prepare("
                INSERT INTO emission (
                    institute_name_id, year_id, environment_id,
                    pollutant_id, unit_id, quantity
                ) VALUES (
                    :institute_name_id, :year_id, :environment_id,
                    :pollutant_id, :unit_id, :quantity
                )
            ");

                $insert->execute([
                    'institute_name_id' => $id_institute_name,
                    'year_id' => $id_year,
                    'environment_id' => $id_environment,
                    'pollutant_id' => $id_waste_label,
                    'unit_id' => $id_unit,
                    'quantity' => $quantity
                ]);
            } else {
                echo " Donnée déjà existante pour $institute_name ($year - $environment - $waste)\n";
            }

        } catch (PDOException $e) {
            echo "Erreur dans SetEmission : " . $e->getMessage();
        }
    }
}
