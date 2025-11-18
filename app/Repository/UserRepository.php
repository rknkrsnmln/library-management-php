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
        $statement = $this->connection->prepare("INSERT INTO users(id, name, password, role, status) VALUES (?, ?, ?, ?, ?)");
        $statement->execute([
            $user->id, $user->name, $user->password, $user->role, $user->status
        ]);
        return $user;
    }

    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $statement->execute([
            $user->name, $user->password, $user->id
        ]);
        return $user;
    }

    public function findById(string $id): ?User
    {
        $statement = $this->connection->prepare("SELECT id, name, password, role, status FROM users WHERE id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                $user->role = $row['role'];
                $user->status = $row['status'];
                return $user;
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