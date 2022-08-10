<?php

namespace Models;

use Carbon\Carbon;

class Meet extends Model
{
    protected  $table = 'matches';
    protected  $findKey = 'id';
    protected  $allKey = 'date';

    public function allWithTeams(): array
    {
        $matchesInfosRequest = 'SELECT * FROM matches JOIN participations p on matches.id = p.match_id JOIN teams t on p.team_id = t.id ORDER BY matches.id, is_home;';
        $pdoSt = $this->pdo->query($matchesInfosRequest);

        return $pdoSt->fetchAll();
    }

    public function allWithTeamsGrouped(array $allWithTeams): array
    {
        $matchesWithTeams = [];
        $m = null;
        foreach ($allWithTeams as $match) {
            if (!$match->is_home) {
                $m = new \stdClass();
                $d = new \DateTime();
                $d->setTimestamp(((int)$match->date) / 1000);
                $m->match_date = $d;
                $m->away_team = $match->name;
                $m->away_team_goals = (int)$match->goals;
                $m->away_team_logo = $match->file_name;
            } else {
                $m->home_team = $match->name;
                $m->home_team_goals = (int)$match->goals;
                $m->home_team_logo = $match->file_name;
                $matchesWithTeams[] = $m;
            }
        }

        return $matchesWithTeams;
    }

    public function save(array $match)
    {
        $insertMatchRequest = 'INSERT INTO matches(`date`, `slug`) VALUES (:date, :slug)';
        $pdoSt = $this->pdo->prepare($insertMatchRequest);
        $pdoSt->execute([':date' => $match['date'], ':slug' => '']);
        $id = $this->pdo->lastInsertId();

        $insertParticipationRequest = 'INSERT INTO participations(`match_id`, `team_id`, `goals`,`is_home`) VALUES (:match_id, :team_id, :goals, :is_home)';
        $pdoSt = $this->pdo->prepare($insertParticipationRequest);
        $pdoSt->execute([
            ':match_id' => $id,
            ':team_id' => $match['home-team'],
            ':goals' => $match['home-team-goals'],
            ':is_home' => 1
        ]);
        $pdoSt = $this->pdo->prepare($insertParticipationRequest);
        $pdoSt->execute([
            ':match_id' => $id,
            ':team_id' => $match['away-team'],
            ':goals' => $match['away-team-goals'],
            ':is_home' => 0
        ]);
    }
}