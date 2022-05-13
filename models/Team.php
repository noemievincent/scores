<?php

namespace Scores\Models;

class Team extends Model
{
    protected string $table = 'teams';
    protected string $findKey = 'id';

    public function all(): array
    {
        $teamsRequest = 'SELECT * FROM teams ORDER BY name';
        $pdoSt = $this->pdo->query($teamsRequest);

        return $pdoSt->fetchAll();
    }

    public function findByName(string $name): \stdClass
    {
        $teamRequest = 'SELECT * FROM teams WHERE name = :name';
        $pdoSt = $this->pdo->prepare($teamRequest);
        $pdoSt->execute([':name' => $name]);

        return $pdoSt->fetch();
    }

    public function save(array $team): void
    {
        try {
            $insertTeamRequest = 'INSERT INTO teams(`name`, `slug`,`file_name`) VALUES (:name, :slug, :file_name)';
            $pdoSt = $this->pdo->prepare($insertTeamRequest);
            $pdoSt->execute([
                ':name' => $team['name'],
                ':slug' => $team['slug'],
                ':file_name' => $team['file_name']
            ]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}

