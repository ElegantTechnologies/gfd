<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once (__DIR__.'/JDbTester_Implementation.php');

final class TestDb_020_PdoTest extends TestCase
{
    use JDbTester_Implementation;
    function test_IntegerBad()
    {
        $this->assertTrue(true, "1 " . __LINE__);
    }

    function test_IncrementNonTraditionalId()
    {
        $FieldId = $this->IdName = 'Id2';
        $tbl = 'test4testIncrementNonTraditionalId'.uniqid();
        $this->trackDoomedTable( $tbl);
        $pdo = \Gfd\Db\Gfdb::Pdo();
        $b = $pdo->query("CREATE TABLE `$tbl` (
				  `$FieldId` int(11) NOT NULL AUTO_INCREMENT,
				  `val1` varchar(255) NULL,
				  `val2` varchar(255) default NULL,
				  `val3` varchar(255) default NULL,
				  `val4` varchar(255) default NULL,
				  `val5` varchar(255) default NULL,
				  UNIQUE KEY `$FieldId` (`$FieldId`) 
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->assertNotFalse($b , var_export($pdo->errorInfo(), true));
        $sql = "INSERT INTO `$tbl` (`val1`) VALUES (:greetings)";
        $statement = $pdo->prepare($sql);

        for ($i = 0; $i <= 1000; $i++) {
            $statement->bindValue(':greetings', 'Hello_'.$i);
            $inserted = $statement->execute();
            $this->assertTrue($inserted);
        }
        $this->PureComment = "Test that I can still increment id even when it isn't the literal string 'id'";
    }

    function test_count()
    {
        $tbl = $this->getWipTableName();
        $pdo = \Gfd\Db\Gfdb::Pdo();

        // --- InsertAsr
        $n = rand(1, 10 * $this->XFactor);

        $stmt = $pdo->prepare("INSERT INTO `$tbl` (`val1`) VALUES (:greetings)");
        $stmtGet = $pdo->prepare("SELECT val1 FROM $tbl WHERE id = :id");
        for ($i = 0; $i < $n; $i = $i + 1) {
            // put in dummy data
            $valInput = $GoodOutput = "Hello World at guid" . uniqid();
            $stmt->bindValue(':greetings',$valInput);
            $stmt->execute();
            $id = $pdo->lastInsertId();
            //--- GetVal
            // Get what you just put in
            $stmtGet->bindValue(':id', $id);// $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
            $stmtGet->execute();
            $valFromDb = $stmtGet->fetchColumn();
            $this->assertTrue($valInput == $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
        }
        $this->PureComment = "Lots of random inserts and gets - testing that I got those values back out.";
    }

    function test_transaction()
    {
        $tbl = $this->getWipTableName();
        $pdo = \Gfd\Db\Gfdb::Pdo();
        $numRowsAtStart = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();

        // Start Trasaction;
        $pdo->beginTransaction();
        $this->assertTrue($pdo->inTransaction());
        // --- InsertAsr
        $n = rand(1, 10 * $this->XFactor);

        $stmt = $pdo->prepare("INSERT INTO `$tbl` (`val1`) VALUES (:greetings)");
        $stmtGet = $pdo->prepare("SELECT val1 FROM $tbl WHERE id = :id");
        for ($i = 0; $i < $n; $i = $i + 1) {
            // put in dummy data
            $valInput = $GoodOutput = "Hello World at guid" . uniqid();
            $stmt->bindValue(':greetings', $valInput);
            $stmt->execute();
            $id = $pdo->lastInsertId();
            //--- GetVal
            // Get what you just put in
            // Get what you just put in
            $stmtGet->bindValue(':id', $id);// $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
            $stmtGet->execute();
            $valFromDb = $stmtGet->fetchColumn();
            $this->assertTrue($valInput == $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
        }
        $numRowsAtEndExpected = $numRowsAtStart + $n;
        $numRowsAtEndActual = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();
        $this->assertTrue($numRowsAtEndExpected == $numRowsAtEndActual);

        $pdo->rollBack();
        $numRowsAtEndActualAfterTransaction = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();
        $this->assertTrue($numRowsAtStart == $numRowsAtEndActualAfterTransaction);

    }
}
