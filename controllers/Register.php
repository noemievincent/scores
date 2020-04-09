<?php


namespace Controllers;


class Register
{
    function create()
    {
        $view = './views/register/create.php';

        return compact('view');
    }
}
