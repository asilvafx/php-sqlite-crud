<?php
class Crud {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Function to check if a column exists in a table
    private function columnExists(string $tableName, string $columnName): bool
    {
        $sql = "PRAGMA table_info($tableName)";
        $stmt = $this->db->query($sql);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
            if ($column['name'] === $columnName) {
                return true;
            }
        }
        return false;
    }

    // Function to add a new column to a table
    private function addColumn(string $tableName, string $columnName, string $columnType): void
    {
        $sql = "ALTER TABLE $tableName ADD COLUMN $columnName $columnType";
        $this->db->exec($sql);
    }

    // CREATE
    public function create(string $tableName, array $data): int
    {
        // Create table if it doesn't exist
        $createTableSQL = "CREATE TABLE IF NOT EXISTS $tableName (id INTEGER PRIMARY KEY AUTOINCREMENT)";
        $this->db->exec($createTableSQL);

        // Prepare the insert statement
        $columns = array_keys($data);
        $placeholders = implode(", ", array_fill(0, count($columns), '?'));
        $values = array_values($data);

        // Check and add columns if they don't exist
        foreach ($columns as $column) {
            if (!$this->columnExists($tableName, $column)) {
                // Assuming all new columns are TEXT for simplicity
                $this->addColumn($tableName, $column, 'TEXT');
            }
        }

        // Now insert the data
        $sql = "INSERT INTO $tableName (" . implode(", ", $columns) . ") VALUES ($placeholders)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            echo "Error inserting data: " . $e->getMessage();
            return 0; // or throw an exception
        }
    }

    // READ
    public function read(string $tableName): array
    {
        // Create table if it doesn't exist
        $createTableSQL = "CREATE TABLE IF NOT EXISTS $tableName (id INTEGER PRIMARY KEY AUTOINCREMENT)";
        $this->db->exec($createTableSQL);

        $sql = "SELECT * FROM $tableName";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error reading data: " . $e->getMessage();
            return [];
        }
    }

    // UPDATE
    public function update(string $tableName, int $id, array $data): bool
    {
        $updates = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
        $sql = "UPDATE $tableName SET $updates WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([...array_values($data), $id]);
            return true; // Return true on success
        } catch (PDOException $e) {
            echo "Error updating data: " . $e->getMessage();
            return false; // Return false on failure
        }
    }

    // DELETE
    public function erase(string $tableName, int $id): bool
    {
        $sql = "DELETE FROM $tableName WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return true; // Return true on success
        } catch (PDOException $e) {
            echo "Error deleting data: " . $e->getMessage();
            return false; // Return false on failure
        }
    }
}