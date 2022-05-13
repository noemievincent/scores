<?php

namespace Scores\Controllers;

use Scores\Models\Fixture;
use Scores\Models\Team;

require('./utils/standings.php');

class Dashboard
{
    public function index(): array
    {
        $fixtureModel = new Fixture();
        $teamModel = new Team();
        $standings = [];
        $fixtures = $fixtureModel->allWithTeamsGrouped($fixtureModel->allWithTeams());
        $teams = $teamModel->all();
        $view = './views/dashboard.php';


        foreach ($fixtures as $fixture) {
            $homeTeam = $fixture->home_team;
            $awayTeam = $fixture->away_team;
            if (!array_key_exists($homeTeam, $standings)) {
                $standings[$homeTeam] = getEmptyStatsArray();
                $standings[$homeTeam]['logo'] = $fixture->home_team_logo;
            }
            if (!array_key_exists($awayTeam, $standings)) {
                $standings[$awayTeam] = getEmptyStatsArray();
                $standings[$awayTeam]['logo'] = $fixture->away_team_logo;
            }
            $standings[$homeTeam]['games']++;
            $standings[$awayTeam]['games']++;

            if ($fixture->home_team_goals === $fixture->away_team_goals) {
                $standings[$homeTeam]['points']++;
                $standings[$awayTeam]['points']++;
                $standings[$homeTeam]['draws']++;
                $standings[$awayTeam]['draws']++;
            } elseif ($fixture->home_team_goals > $fixture->away_team_goals) {
                $standings[$homeTeam]['points'] += 3;
                $standings[$homeTeam]['wins']++;
                $standings[$awayTeam]['losses']++;
            } else {
                $standings[$awayTeam]['points'] += 3;
                $standings[$awayTeam]['wins']++;
                $standings[$homeTeam]['losses']++;
            }
            $standings[$homeTeam]['GF'] += $fixture->home_team_goals;
            $standings[$homeTeam]['GA'] += $fixture->away_team_goals;
            $standings[$awayTeam]['GF'] += $fixture->away_team_goals;
            $standings[$awayTeam]['GA'] += $fixture->home_team_goals;
            $standings[$homeTeam]['GD'] = $standings[$homeTeam]['GF'] - $standings[$homeTeam]['GA'];
            $standings[$awayTeam]['GD'] = $standings[$awayTeam]['GF'] - $standings[$awayTeam]['GA'];

        }

        uasort($standings, static function ($a, $b) {
            if ($a['points'] === $b['points']) {
                return 0;
            }

            return $a['points'] > $b['points'] ? -1 : 1;
        });

        return compact('standings', 'fixtures', 'teams', 'view');
    }
}


