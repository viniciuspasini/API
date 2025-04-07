<?php

namespace database\repository;

use app\models\User;
use database\Connection;
use PDO;

class PdoUserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $stmt = $this->connection->prepare("SELECT id, name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function store(User $user): User
    {
        $stmt = $this->connection->prepare("
        INSERT INTO users (name, email, password) VALUES (:name, :email, :password)
        ");

        $stmt->execute([
            ':name' => $user->getName(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword()
        ]);

        if($this->connection->lastInsertId() > 0){
            $user->setId($this->connection->lastInsertId());
        }

        return $user;
    }

    public function find(int $id): false|User
    {
        $stmt = $this->connection->prepare("SELECT id, name, email, password FROM users WHERE id = :id");
        $stmt->execute([
            ':id' => $id
        ]);
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        if($fetch){
            return new User(
                $fetch['id'],
                $fetch['name'],
                $fetch['email'],
                $fetch['password']
            );
        }
        return false;
    }

    public function auth(string $email, string $password): false|User
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([
            ':email' => $email
        ]);

        if ($stmt->rowCount() < 1) return false;

        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($password, $fetch['password'])) return false;

        return new User(
            $fetch['id'],
            $fetch['name'],
            $fetch['email'],
            $fetch['password']
        );
    }

}