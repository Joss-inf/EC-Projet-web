<?php
class Emissions
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllYears(): array
    {
        $sql = "SELECT id, year FROM year ORDER BY year ASC";
        return $this->executeQuery($sql);
    }

    public function getAllEnvironments(): array
    {
        $sql = "SELECT id, name FROM environment ORDER BY name ASC";
        return $this->executeQuery($sql);
    }

    // Nouvelle méthode : récupérer tous les polluants
    public function getAllPollutants(): array
    {
        $sql = "SELECT id, name FROM pollutant ORDER BY name ASC";
        return $this->executeQuery($sql);
    }

    //  Ajout du filtre $pollutant
    public function searchEmissions(?string $year, ?string $environment, ?string $pollutant, ?string $instituteName, int $limit, int $offset, string $sortOrder): array
    {
        $sql = "
            SELECT 
                i.name AS institute_name, 
                y.year AS year, 
                en.name AS env, 
                p.name AS pollutant,
                e.quantity, 
                u.name AS unit
            FROM emission e
            JOIN unit u ON e.unit_id = u.id
            JOIN year y ON e.year_id = y.id
            JOIN environment en ON e.environment_id = en.id
            JOIN institute_name i ON e.institute_name_id = i.id
            JOIN pollutant p ON e.pollutant_id = p.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($year)) {
            $sql .= " AND y.year = :year";
            $params[':year'] = $year;
        }

        if (!empty($environment)) {
            $sql .= " AND en.name = :env";
            $params[':env'] = $environment;
        }

        if (!empty($pollutant)) {
            $sql .= " AND p.name = :pollutant";
            $params[':pollutant'] = $pollutant;
        }

        if (!empty($instituteName)) {
            $sql .= " AND i.name LIKE :institute";
            $params[':institute'] = "%$instituteName%";
        }

        $sql .= " ORDER BY e.quantity $sortOrder";
        $sql .= " LIMIT :limit OFFSET :offset";

        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        return $this->executeQueryWithLimit($sql, $params);
    }

    private function executeQuery(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return [];
        }
    }

    private function executeQueryWithLimit(string $sql, array $params): array
    {
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL avec pagination : " . $e->getMessage());
            return [];
        }
    }
}
