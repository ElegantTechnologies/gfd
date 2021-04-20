<?php
declare(strict_types=1);
trait JDbTester_Implementation {
    public array $tbls = [];

    public function trackDoomedTable(string $tableName_thatTearDownShouldDelete): void {
        $this->tbls[] = $tableName_thatTearDownShouldDelete;
    }

    private string $tbl;
    public function getWipTableName(): string {
        return $this->tbl;
    }
    public function setUp(): void
    {
        $this->tbl = "test_" . uniqid();
        $this->tbls[] = $this->tbl;
        $tbl = $this->tbl;
        $this->XFactor = 5; //INPUT: increase this to really stress test things

        // Connect to db

        #$this->conn = new JjrClsDb($dsnStr);//mysql://root:pwd@localhost/mydb?persist
        #$this->conn->ObjAdo->debug = true;
        #$this->conn->Execute("DROP TABLE `$tbl`");
        #$this->conn->Execute("CREATE TABLE `$tbl` (
        #		  `id` int(11) NOT NULL,
        #		  `val1` varchar(255) default NULL
        #		)");

        $sqlCreateTable =<<<SQL
        CREATE TABLE `$tbl` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `val1` varchar(255) default NULL,
                  `val2` varchar(255) default NULL,
                  `val3` varchar(255) default NULL,
                  `val4` varchar(255) default NULL,
                  `val5` varchar(255) default NULL,
                  `i1` INTEGER default NULL,
                  UNIQUE KEY `id` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        SQL;

        \Gfd\Db\Gfdb::Pdo()->prepare($sqlCreateTable)->execute();
        $this->IdName = 'id';

    }

    public function tearDown(): void
    {
        // Erase all tables I created, and their derivitives

        $ArrTablesFromDb = \Gfd\Db\Gfdb::One()->getTables();
        $ArrTables = $this->tbls;
        foreach ($ArrTables as $StrTable) {
            \Gfd\Db\Gfdb::Pdo()->prepare("DROP TABLE  IF EXISTS `$StrTable`")->execute();
            //            foreach ($ArrTablesFromDb as $OtherTable) {
            //                if (EtStringConvert::BeginsWith($OtherTable, $StrTable)) {
            //                    $GJjrDbConn->Execute("DROP TABLE  IF EXISTS `{$OtherTable}`");
            //                }
            //            }
        }
    }
}