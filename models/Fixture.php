<?php

namespace Scores\Models;

use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;

class Fixture extends Model
{
    protected string $table = 'fixtures';
    protected string $findKey = 'id';

    public function all(): array
    {
        $sql = <<<SQL
            SELECT * 
            FROM fixtures 
            ORDER BY date
        SQL;
        $pdoSt = $this->pdo->query($sql);

        return $pdoSt->fetchAll();
    }

    public function allWithTeams(): array
    {
        $sql = <<<SQL
            SELECT * 
            FROM fixtures 
                JOIN participations p on fixtures.id = p.fixture_id 
                JOIN teams t on p.team_id = t.id 
            ORDER BY fixtures.id, is_home
        SQL;
        $pdoSt = $this->pdo->query($sql);

        return $pdoSt->fetchAll();
    }

    public function allWithTeamsGrouped(array $allWithTeams): array
    {
        $fixturesWithTeams = [];
        $m = null;
        foreach ($allWithTeams as $fixture) {
            if (!$fixture->is_home) {
                $m = new \stdClass();
                $d = Carbon::createFromFormat('Y-m-d H:i:s', $fixture->date);
                $m->fixture_date = $d;
                $m->away_team = $fixture->name;
                $m->away_team_goals = $fixture->goals;
                $m->away_team_logo = $fixture->file_name;
            } else {
                $m->home_team = $fixture->name;
                $m->home_team_goals = $fixture->goals;
                $m->home_team_logo = $fixture->file_name;
                $fixturesWithTeams[] = $m;
            }
        }
        return $fixturesWithTeams;
    }

    #[NoReturn] public function save(array $fixture): void
    {
        $sql = <<<SQL
            INSERT INTO fixtures(date, slug) VALUES (:date, :slug)
        SQL;
        $pdoSt = $this->pdo->prepare($sql);
        $pdoSt->execute([
            ':date' => $fixture['date'],
            ':slug' => '',
        ]);
        $id = $this->pdo->lastInsertId();

        $sql = <<<SQL
            INSERT INTO participations(fixture_id, team_id, goals, is_home) 
            VALUES (:fixture_id, :team_id, :goals, :is_home)
        SQL;
        $pdoSt = $this->pdo->prepare($sql);
        $pdoSt->execute([
            ':fixture_id' => $id,
            ':team_id' => $fixture['home-team'],
            ':goals' => $fixture['home-team-goals'],
            ':is_home' => 1,
        ]);
        $pdoSt = $this->pdo->prepare($sql);
        $pdoSt->execute([
            ':fixture_id' => $id,
            ':team_id' => $fixture['away-team'],
            ':goals' => $fixture['away-team-goals'],
            ':is_home' => 0,
        ]);
    }
}


