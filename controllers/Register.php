<?php


namespace Scores\Controllers;


use Scores\Models\User;
use JetBrains\PhpStorm\NoReturn;

class Register
{
    public function create(): array
    {
        $view = './views/register/create.php';

        return compact('view');
    }

    #[NoReturn] public function store(): void
    {
        //Collecte et validation des données
        //À vous pour la validation
        //⚠️ ce qui suit est très imprudent ! Il faut valider les données avant !
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        //Sauvegarde de l’utilisateur
        $userModel = new User();
        $userModel->save(compact('name', 'email', 'password'));
        header('Location: index.php');

        exit();

    }
}
