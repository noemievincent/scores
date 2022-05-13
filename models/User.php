<?php


namespace Scores\Models;


use JetBrains\PhpStorm\NoReturn;

class User extends Model
{
    protected string $table = 'users';
    protected string $findKey = 'email';

    #[NoReturn] public function save(array $user): void
    {
        try {
            $insertUserRequest = 'INSERT INTO users(`name`, `email`, `password`) VALUES (:name, :email, :password)';
            $pdoSt = $this->pdo->prepare($insertUserRequest);
            $pdoSt->execute([
                ':name' => $user['name'],
                ':email' => $user['email'],
                ':password' => $user['password'],
            ]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
