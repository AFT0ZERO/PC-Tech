<?php

namespace App\Repositories;

class ComponentSpecReader
{
    public function searchAutocomplete(string $query, string $category, string $dbPath, int $limit = 5): array
    {
        $pdo = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare(
            'SELECT name, specs_json FROM components WHERE search_text LIKE ? AND category = ? LIMIT ?'
        );
        $stmt->execute(["%$query%", $category, $limit]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
