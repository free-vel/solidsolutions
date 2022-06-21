<?php
class Db
{
    /** @var \PDO */
    private $pdo;

    public function __construct()
    {
        $dbOptions = (require __DIR__ . '/settings.php')['db'];

        $this->pdo = new \PDO(
            'mysql:host=' . $dbOptions['host'] . ';port='.$dbOptions['port'].';dbname=' . $dbOptions['dbname'],
            $dbOptions['user'],
            $dbOptions['password']
        );
        $this->pdo->exec('SET NAMES UTF8');

    }

    public function query(string $sql, $params = []): ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = @$sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll();
    }

	public function query_res(string $sql, $params = []){
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth;
    }

	public function fetch($res): ?array
    {
        $result = $res->fetch();

        if (false === $result) {
            return null;
        }

        return $result;
    }

	public function last_insert_id(){
		return $this->pdo->lastInsertId();
	}
}