<?php

namespace Library\PHP\MVC\Config;

use Exception;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection(string $env = "test"): \PDO
    {
        if (self::$pdo == null) {
            // create new PDO
            require_once __DIR__ . '/../../config/database.php';
            $config = getDatabaseConfig();
            self::$pdo = new \PDO(
                $config['database'][$env]['url'],
                $config['database'][$env]['username'],
                $config['database'][$env]['password']
            );
        }

        return self::$pdo;
    }

    public static function beginTransaction(): void
    {
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction(): void
    {
        self::$pdo->commit();
    }

    public static function rollbackTransaction(): void
    {
        self::$pdo->rollBack();
    }

    /**
     * Create a new user in the users table.
     *
     * @param string $id The user's unique ID
     * @param string $name The user's name
     * @param string $password The user's password (plain text, will be hashed)
     * @param string $status The user's status ('active' or 'nonactive')
     * @param string $role The user's role ('member' or 'admin')
     * @throws Exception If there is any error during the insertion
     */
    public static function createUser(string $id, string $name, string $password, string $status = 'active', string $role = 'member'): void
    {
        try {
            // Start a transaction
            self::beginTransaction();

            // Hash the password before storing
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Prepare the SQL statement to insert the user
            $stmt = self::$pdo->prepare(
                "INSERT INTO users (id, name, password, status, role)
                 VALUES (:id, :name, :password, :status, :role)"
            );

            // Execute the statement with the provided values
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':password' => $hashedPassword,
                ':status' => $status,
                ':role' => $role
            ]);

            // Commit the transaction
            self::commitTransaction();
        } catch (Exception $e) {
            // Rollback the transaction if there's an error
            self::rollbackTransaction();
            // Throw the exception so it can be caught by the calling code
            throw new Exception("Failed to create user: " . $e->getMessage());
        }
    }
}