<?php


namespace Models;


class User extends Model
{
    public function find(string $email): \StdClass
    {
        $userRequest = 'SELECT * FROM users WHERE email = :email';
        $pdoSt = $this->pdo->prepare($userRequest);
        $pdoSt->execute([':email' => $email]);

        return $pdoSt->fetch();
    }

    function save(array $user)
    {
        try {
            $insertUserRequest = 'INSERT INTO users(`name`, `email`, `password`) VALUES (:name, :email, :password)';
            $pdoSt = $this->pdo->prepare($insertUserRequest);
            $pdoSt->execute([
                ':name' => $user['name'],
                ':email' => $user['email'],
                ':password' => $user['password']
            ]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
