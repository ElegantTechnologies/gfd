<?php

use PHPUnit\Framework\TestCase;

require_once (__DIR__.'/JDbTester_Implementation.php');

class Vlto {
    public  $i1;
}

class Vlto2 {
    public string $val1;
    public int $i1;
    
}

class TestDb_030_ObjTest extends TestCase
{
    use JDbTester_Implementation;

    function test_count()
    {
            $tbl = $this->getWipTableName();
            $pdo = \Gfd\Db\Gfdb::Pdo();
            $numRowsAtStart = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();

            $this->assertFalse($pdo->inTransaction());
            // --- InsertAsr
            $n = rand(1, 10 * $this->XFactor);

            $stmt = $pdo->prepare("INSERT INTO `$tbl` (`i1`) VALUES (:greetings)");
            $stmtGet = $pdo->prepare("SELECT i1 FROM $tbl WHERE id = :id");
            for ($i = 0; $i < $n; $i = $i + 1) {
                // put in dummy data
                $valInput = $GoodOutput = $i*$i;
                $stmt->bindValue(':greetings', $valInput);
                $stmt->execute();
                $id = $pdo->lastInsertId();
                //--- GetVal
                // Get what you just put in
                $stmtGet->bindValue(':id', $id);// $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
                $stmtGet->execute();
                $objFromDb = $stmtGet->fetchObject();
                $valFromDb = $objFromDb->i1; // FYI: ->i1 is now a string
                $this->assertTrue($valInput == $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
                #$this->assertTrue($valInput === $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
            }
            $numRowsAtEndExpected = $numRowsAtStart + $n;
            $numRowsAtEndActual = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();
            $this->assertTrue($numRowsAtEndExpected == $numRowsAtEndActual);
    }

    function test_objPreI()
    {
        $tbl = $this->getWipTableName();
        $pdo = \Gfd\Db\Gfdb::Pdo();
        $numRowsAtStart = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();

        $this->assertFalse($pdo->inTransaction());
        // --- InsertAsr
        $n = rand(1, 10 * $this->XFactor);

        $stmt = $pdo->prepare("INSERT INTO `$tbl` (`i1`) VALUES (:i)");
        $stmtGet = $pdo->prepare("SELECT i1 FROM $tbl WHERE id = :id");
        for ($i = 0; $i < $n; $i = $i + 1) {
            // put in dummy data
            $valInput = $GoodOutput = $i*$i;
            $stmt->bindValue(':i', $i);
            $greeting = 'Hello_'. $i;
            #$stmt->bindValue(':greetings',$greeting);
            $stmt->bindValue(':i',$valInput);
            $stmt->execute();
            $id = $pdo->lastInsertId();
            //--- GetVal
            // Get what you just put in
            // Get what you just put in
            $stmtGet->bindValue(':id', $id);// $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
            $stmtGet->execute();
            $objFromDb = $stmtGet->fetchObject(Vlto2::class);
            $valFromDb = $objFromDb->i1; // FYI: ->i1 is now a string
            $this->assertTrue($valInput == $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
            $this->assertTrue($valInput === $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
            $this->assertTrue($valInput === $objFromDb->i1, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
        }
        $numRowsAtEndExpected = $numRowsAtStart + $n;
        $numRowsAtEndActual = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();
        $this->assertTrue($numRowsAtEndExpected == $numRowsAtEndActual);
    }

    function test_objValAndI()
    {
        $tbl = $this->getWipTableName();
        $pdo = \Gfd\Db\Gfdb::Pdo();
        $numRowsAtStart = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();

        $this->assertFalse($pdo->inTransaction());
        // --- InsertAsr
        $n = rand(1, 10 * $this->XFactor);

        $stmt = $pdo->prepare("INSERT INTO `$tbl` (`i1`, `val1`) VALUES (:i, :val1greeting)");
        $stmtGet = $pdo->prepare("SELECT i1, val1 FROM $tbl WHERE id = :id");
        for ($i = 0; $i < $n; $i = $i + 1) {
            // put in dummy data
            $valInput = $GoodOutput = $i*$i;
            $stmt->bindValue(':i', $i);
            $greeting = 'Hello_'. $i;
            $stmt->bindValue(':val1greeting',$greeting);
            $stmt->bindValue(':i',$valInput);
            $stmt->execute();
            $id = $pdo->lastInsertId();
            //--- GetVal
            // Get what you just put in
            // Get what you just put in
            $stmtGet->bindValue(':id', $id);// $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
            $stmtGet->execute();
            $objFromDb = $stmtGet->fetchObject(Vlto2::class);
            $valFromDb = $objFromDb->i1; // FYI: ->i1 is now a string
            $this->assertTrue($valInput == $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
            $this->assertTrue($valInput === $valFromDb, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
            $this->assertTrue($valInput === $objFromDb->i1, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
            $this->assertTrue($greeting === $objFromDb->val1, "id = $id, Input=$valInput, Expected=>$GoodOutput != Actual=>$valFromDb.  They should be the same");
        }
        $numRowsAtEndExpected = $numRowsAtStart + $n;
        $numRowsAtEndActual = $pdo->query("SELECT count(id) FROM $tbl")->fetchColumn();
        $this->assertTrue($numRowsAtEndExpected == $numRowsAtEndActual);
    }


}
