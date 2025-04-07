<?php

namespace app\models;

class User
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private string $password,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}