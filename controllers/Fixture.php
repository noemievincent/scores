<?php

namespace Scores\Controllers;

use Scores\Models\Team;
use JetBrains\PhpStorm\NoReturn;

class Fixture{
    #[NoReturn] public function store(): void
    {
        $fixtureModel = new \Scores\Models\Fixture();
        $fixtureDate = $_POST['fixture-date'];
        $homeTeam = $_POST['home-team'];
        $awayTeam = $_POST['away-team'];
        $homeTeamGoals = $_POST['home-team-goals'];
        $awayTeamGoals = $_POST['away-team-goals'];

        $fixture = [
            'date' => $fixtureDate,
            'home-team' => $homeTeam,
            'home-team-goals' => $homeTeamGoals,
            'away-team-goals' => $awayTeamGoals,
            'away-team' => $awayTeam
        ];

        $fixtureModel->save($fixture);
        header('Location: index.php');
        exit();
    }

    public function create(): array
    {
        $teamModel = new Team();
        $teams = $teamModel->all();
        $view = './views/fixture/create.php';

        return compact('view', 'teams');
    }
}



