<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OpenDbComponentSeeder extends Seeder
{
    private string $sourceDir;
    private string $dbPath;
    private bool $cloned = false;

    public function run(): void
    {
        $this->sourceDir = base_path('database/buildcores-open-db/open-db');
        $this->dbPath = base_path('database/components.sqlite');

        $this->ensureSourceDir();
        $this->ensureDbParentDir();

        $this->command?->info("Starting indexing from {$this->sourceDir}...");

        $conn = $this->initDb();
        $this->scanAndIndex($conn);
        $conn->close();

        $this->cleanup();
    }

    private function ensureSourceDir(): void
    {
        if (is_dir($this->sourceDir)) {
            return;
        }

        $repoParent = dirname($this->sourceDir);
        $this->command?->info("source_db_dir not found. Cloning repository into {$repoParent}...");
        exec("git clone https://github.com/buildcores/buildcores-open-db.git " . escapeshellarg($repoParent));
        $this->cloned = true;
    }

    private function ensureDbParentDir(): void
    {
        $dbDir = dirname($this->dbPath);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0777, true);
        }
    }

    private function initDb(): \SQLite3
    {
        if (file_exists($this->dbPath)) {
            unlink($this->dbPath);
        }

        $conn = new \SQLite3($this->dbPath);
        $conn->exec("
            CREATE TABLE components (
                id TEXT PRIMARY KEY,
                category TEXT,
                name TEXT,
                search_text TEXT,
                specs_json TEXT
            )
        ");
        $conn->exec('CREATE INDEX idx_search_text ON components(search_text)');
        return $conn;
    }

    private function scanAndIndex(\SQLite3 $db): void
    {
        $count = 0;
        $categories = scandir($this->sourceDir);

        foreach ($categories as $category) {
            if ($category === '.' || $category === '..') {
                continue;
            }

            $catPath = $this->sourceDir . '/' . $category;
            if (!is_dir($catPath)) {
                continue;
            }

            $files = scandir($catPath);
            foreach ($files as $file) {
                if (!str_ends_with($file, '.json')) {
                    continue;
                }

                $filePath = $catPath . '/' . $file;
                try {
                    $content = file_get_contents($filePath);
                    $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

                    if (!isset($data['metadata']['name'])) {
                        continue;
                    }

                    $name = $data['metadata']['name'];
                    $partNumbers = $data['metadata']['part_numbers'] ?? [];

                    $searchTerms = [strtolower($name)];
                    foreach ($partNumbers as $pn) {
                        if (is_string($pn)) {
                            $searchTerms[] = strtolower($pn);
                        }
                    }

                    $searchText = implode(' | ', $searchTerms);
                    $fileUuid = basename($file, '.json');
                    $specsJson = json_encode($data, JSON_UNESCAPED_UNICODE);

                    $stmt = $db->prepare("
                        INSERT INTO components (id, category, name, search_text, specs_json)
                        VALUES (:id, :category, :name, :search_text, :specs_json)
                    ");
                    $stmt->bindValue(':id', $fileUuid, SQLITE3_TEXT);
                    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
                    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
                    $stmt->bindValue(':search_text', $searchText, SQLITE3_TEXT);
                    $stmt->bindValue(':specs_json', $specsJson, SQLITE3_TEXT);
                    $stmt->execute();
                    $count++;
                } catch (\Exception $e) {
                    $this->command?->error("Error parsing {$filePath}: {$e->getMessage()}");
                }
            }
        }

        $this->command?->info("Successfully indexed {$count} components into SQLite.");
    }

    private function cleanup(): void
    {
        if ($this->cloned) {
            $this->command?->info("Cleaning up cloned repository...");
            exec("rm -rf " . escapeshellarg(dirname($this->sourceDir)));
        }
    }
}
