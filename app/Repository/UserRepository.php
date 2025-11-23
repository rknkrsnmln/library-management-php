<?php

namespace Library\PHP\MVC\Repository;


use Library\PHP\MVC\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User
    {
        // Use named placeholders
        $statement = $this->connection->prepare(
            "INSERT INTO users (id, name, password, role, status) 
             VALUES (:id, :name, :password, :role, :status)"
        );

        // Execute with named parameters
        $statement->execute([
            ':id' => $user->id,
            ':name' => $user->name,
            ':password' => $user->password,
            ':role' => $user->role,
            ':status' => $user->status
        ]);

        return $user;
    }

    public function update(User $user): User
    {
        // Named placeholders disini
        $statement = $this->connection->prepare(
            "UPDATE users 
             SET name = :name, password = :password 
             WHERE id = :id"
        );

        // Execute
        $statement->execute([
            ':name' => $user->name,
            ':password' => $user->password,
            ':id' => $user->id
        ]);

        return $user;
    }

    public function findById(string $id): ?User
    {
        // Named placeholders disini
        $statement = $this->connection->prepare(
            "SELECT id, name, password, role, status 
             FROM users 
             WHERE id = :id"
        );

        // Execute
        $statement->execute([':id' => $id]);

        try {
            if ($row = $statement->fetch()) {
//                $user = new User();
//                $user->id = $row['id'];
//                $user->name = $row['name'];
//                $user->password = $row['password'];
//                $user->role = $row['role'];
//                $user->status = $row['status'];
//                return $user;
                return $this->getUser($row);
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE from users");
    }

    /**
     * Find all books
     * @return User[] Returns an array of Book objects
     */
    public function findAllUsers(): array
    {
        $statement = $this->connection->prepare("SELECT * FROM users");
        $statement->execute();
        $usersData = $statement->fetchAll();
        $userData = [];
        foreach ($usersData as $user) {
            $userData[] = $this->getUser($user);
        }
        return $userData;
    }

    /**
     * @param mixed $row
     * @return User
     */
    private function getUser(array $row): User
    {
        $user = new User();
        $user->id = $row['id'];
        $user->name = $row['name'];
        $user->password = $row['password'];
        $user->role = $row['role'];
        $user->status = $row['status'];
        return $user;
    }
}