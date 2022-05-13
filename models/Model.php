<?php


namespace Scores\Models;


class Model
{
    protected \PDO $pdo;
    protected string $table;
    protected string $findKey;

    public function __construct()
    {
        try {
            $connection = new \PDO('mysql:host=127.0.0.1;dbname=scores', 'root', '');
            $connection->setAttribute(\PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        $this->pdo = $connection;
    }

    public function find(string $key): \StdClass
    {
        $request = 'SELECT * FROM '.$this->table.' WHERE '.$this->findKey.' = :'.$this->findKey;
        $pdoSt = $this->pdo->prepare($request);
        $pdoSt->execute([':'.$this->findKey => $key]);

        return $pdoSt->fetch();
    }

    public function all()
    {
        // TODO : À vous
    }
}
