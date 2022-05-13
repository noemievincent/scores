<?php


namespace Scores\Controllers;


use JetBrains\PhpStorm\NoReturn;

class Login
{
    public function create(): array
    {
        $view = './views/login/create.php';

        return compact('view');
    }

    #[NoReturn] public function check(): void
    {
        // Collecte et validation des données de l’utilisateur envoyées avec le formulaire
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Identification
        $userModel = new \Scores\Models\User();
        $user = $userModel->find($email);

        // Authentification
        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
        } else {
            header('Location: index.php?action=view&resource=login-form');
        }

        exit();

    }

    #[NoReturn] public function delete(): void
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        header('Location: index.php');
        exit();
    }
}
