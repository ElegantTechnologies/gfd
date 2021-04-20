<?php
declare(strict_types=1);

namespace Gfd\Db;

use ADOConnection;

class Gfdb
{
    use Gfdb_shortcuts_implementation;

    private static Gfdb $singleton;
    protected \PDO $pdo;

    public function __construct()
    {
        if (!isset(static::$singleton)) {
            if (defined('DB_NAME')) {
                $DB_DBNAME = DB_NAME;
                $DB_USER = DB_USER;
                $DB_PASSWORD = DB_PASSWORD;
                $DB_HOST = DB_HOST;
            } else {
                $DB_DBNAME = $GLOBALS['DB_DBNAME'];
                $DB_USER = $GLOBALS['DB_USER'];
                $DB_PASSWORD = $GLOBALS['DB_PASSWORD'];
                $DB_HOST = $GLOBALS['DB_HOST'];
                assert(!empty($DB_DBNAME));
                assert(!empty($DB_USER));
                assert(!empty($DB_PASSWORD));
                assert(!empty($DB_HOST));
            }
            $dsnStr = "mysql://$DB_USER:$DB_PASSWORD@$DB_HOST/$DB_DBNAME?persist";
            try { //https://stackoverflow.com/a/6263868
                $dbh = new \PDO("mysql:host={$DB_HOST};dbname={$DB_DBNAME}",
                    $DB_USER,
                    $DB_PASSWORD,
                    array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
                $this->pdo = $dbh;
            } catch (\PDOExfception $ex) {
                die(\json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
            }
            #$aPdo = new \PDO($dsnStr, null, null, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            #$this->pdo = $aPdo;
            assert($this->pdo);
        }
    }

    public static function One(): self
    {
        if (!isset(static::$singleton)) {
            $m = new static();
            static::$singleton = $m;
        }
        return static::$singleton;
    }


    public static function Pdo(): \PDO
    {
        return static::One()->getPdo();
    }


    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

}



