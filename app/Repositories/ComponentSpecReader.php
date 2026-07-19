<?php

namespace App\Repositories;

class ComponentSpecReader
{
    public function findExact(string $query, string $dbPath): ?array
    {
        $pdo = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT name, specs_json FROM components WHERE name = ?");
        $stmt->execute([$query]);
        $exact = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($exact) {
            return json_decode($exact['specs_json'], true);
        }
        return null;
    }

    public function searchLike(string $query, string $dbPath): array
    {
        $pdo = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT name, specs_json FROM components WHERE search_text LIKE ?");
        $stmt->execute(["%$query%"]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchWordImploded(array $words, string $dbPath): array
    {
        $pdo = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT name, specs_json FROM components WHERE search_text LIKE ?");
        $stmt->execute(['%' . implode('%', $words) . '%']);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
