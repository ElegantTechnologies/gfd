<?php

use PHPUnit\Framework\TestCase;

require_once (__DIR__.'/JDbTester_Implementation.php');

class TestDb_030_Shortcuts extends TestCase
{
    use JDbTester_Implementation;


/*
        function testIncrementNonTraditionalId()
        {
            $FieldId = $this->IdName = 'Id2';
            $tbl = 'test'.uniqid();
            $pdo = \Gfd\Db\Gfdb::Pdo();
            $pdo->query("CREATE TABLE `$tbl` (
                      `$FieldId` int(11) NOT NULL,
                      `val1` varchar(255) default NULL,
                      `val2` varchar(255) default NULL,
                      `val3` varchar(255) NOT NULL,
                      `val4` varchar(255) NOT NULL,
                      `val5` varchar(255) NOT NULL,
                      UNIQUE KEY `$FieldId` (`$FieldId`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;")->execute();
            $sql = "INSERT INTO `$tbl` (`val1`) VALUES (:greetings)";
            $statement = $pdo->prepare($sql);

            for ($i = 0; $i <= 5; $i++) {
                $statement->bindValue(':greetings', 'Hello_'.$i);
                $inserted = $statement->execute();
                $this->assertTrue($inserted);
            }
            $this->PureComment = "Test that I can still increment id even when it isn't the literal string 'id'";

        }

        function testInsertId1()
        {
            $tbl = $this->tbl;
            $n = rand(1, 10 * $this->XFactor);
            $n = 5;
            for ($i = 0; $i < $n; $i = $i + 1) {
                // put in dummy data
                $val1 = $Input = "Hello World at guid" . uniqid();
                $AsrIn = array('val1' => $val1, 'val5' => "hi from testInsertId1");
                $id1 = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $val2 = $Input = "Hello World at guid" . uniqid();
                $AsrIn = array('val1' => $val2, 'val5' => "hi from testInsertId1");
                $id2 = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $this->assertTrue($id1 != $id2, "1) id1 and id2 are the same ($id1) - which means your sequencing isn't happening proeprly");

                $val3 = $Input = "Hello World at guid" . uniqid();
                $AsrIn = array('val1' => $val3, 'val5' => "hi from testInsertId1");
                $id3 = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $this->assertTrue($id1 != $id3, "1) id1 and id3 are the same ($id1) - which means your sequencing isn't happening proeprly");
                $this->assertTrue($id2 != $id3, "1) id2 and id3 are the same ($id1) - which means your sequencing isn't happening proeprly");
                $this->assertTrue($id3 != 1, "1) id3 was '1' which is very suspicious.  i can under the first insert being 1, even the second one (imagine the first had id=0), but why in world would the 3rd insert have an id of 1");
            }

            $this->PureComment = "Test the insert doesn't just always return 1 for the id. id1=$id1, id2=$id2, id3=$id3";
        }

        function testInsertId2()
        {
            $tbl = $this->tbl;
            $n = rand(1, 10 * $this->XFactor);
            for ($i = 0; $i < $n; $i = $i + 1) {
                // put in dummy data
                $val1 = $Input = "Hello World at guid" . uniqid();
                $AsrIn = array('val1' => $val1);
                $id1 = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $val2 = $Input = "Hello World at guid" . uniqid();
                $AsrIn = array('val1' => $val2);
                $id2 = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $val3 = $Input = "Hello World at guid" . uniqid();
                $AsrIn = array('val1' => $val3);
                $id3 = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $Out = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id1");
                $this->assertTrue($val1 == $Out, "1) I didn't get right val when I mixed up order of retrieving vals. id = $id1  Got '$Out', expected '$val1'");

                $Out = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id3");
                $this->assertTrue($val3 == $Out, "2) I didn't get right val when I mixed up order of retrieving vals.  id = $id3   Got '$Out', expected '$val3'");

                $Out = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id2");
                $this->assertTrue($val2 == $Out, "3) I didn't get right val when I mixed up order of retrieving vals. id = $id2    Got '$Out', expected '$val2'");
            }

            $this->PureComment = "Test the insert is returning 'id' properly";
        }
*/
        function testForcedSuccess()
        {
            $this->assertTrue(1 == 1, "1 is 1");
            $this->PureComment = "abstract";
        }
/*
        function testExecuteSimplest()
        {
            $tbl = $this->getWipTableName();
            $pdo = \Gfd\Db\Gfdb::Pdo();
            $r = $pdo->query ("SELECT * FROM $tbl ")->fetchAll();
            $this->PureComment = "can i select anything";
            $this->assertTrue(1 == 1, "1 is 1");
        }

            function test1()
            {
                $tbl = $this->getWipTableName();

                // --- InsertAsr
                $n = rand(1, 10 * $this->XFactor);

                for ($i = 0; $i < $n; $i = $i + 1) {
                    // put in dummy data
                    $val = $Input = $GoodOutput = "Hello World at guid" . uniqid();
                    $id = $this->conn->InsertAsr($tbl, array('val1' => $val), $this->IdName);
                    $stmt = $db->prepare("...");
                    $stmt->execute();
                    $id = $db->lastInsertId();
                    //--- GetVal
                    // Get what you just put in
                    $valFromDb = $GeneratedOutput = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
                    $this->assertTrue($val == $GeneratedOutput, "id = $id, Input=$Input, Expected=>$GoodOutput != Actual=>$GeneratedOutput.  They should be the same");
                }
                $ut2 = $gUprobe->stop("DB::1", $ut);
                $utDelta = $ut2 - $ut;
                $comment = " $i round-trips in " . number_format($utDelta, 3) . " seconds. (" . (number_format($utDelta / $n, 5)) . ") sec per item or (" . (number_format($n / $utDelta, 1)) . ") roundtrips per second";
                $this->PureComment = "Lots of random inserts and gets - testing that I got those values back out." . $comment;
            }


            function testTrans1()
            {
                $tbl = $this->tbl;
                // ---- test transactions
                $this->conn->StartTrans();
                $val = "Hello World at guid" . uniqid();
                $id = $this->conn->InsertAsr($tbl, array('val1' => $val), $this->IdName);

                //--- GetVal
                // Get what you just put in
                $valFromDb = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
                $this->assertTrue($val == $valFromDb, "didn't work on the INSIDE of the trans");

                $this->conn->CompleteTrans();
                $this->PureComment = "See if base StartTrans and FailTrans avoid crashing";
            }


            function testTrans2()
            {
                $tbl = $this->tbl;
                //--- test that transactions dies upon bad sql
                $this->conn->StartTrans();
                $val = "Hello World at guid" . uniqid();
                $id = $this->conn->InsertAsr($tbl, array('val1' => $val), $this->IdName);

                //--- fail on purpose
                $valFromDb = $this->conn->GetVal("SELECT blah1 FROM PretendTableThatDoesntExistSoWillFail", False);

                $this->conn->CompleteTrans();
                $valFromDb = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id", false);// false to not die on failure
                $this->assertTrue($val != $valFromDb, "Transaction failed - it should have rolled back because I did bad sql, however, I was able to get the value from id=$id - but the rollback should have nixed it. val=$val, but valFromDb = $valFromDb when it should be blank because we wanted to rolled back out of the database ");

                $this->PureComment = "test that transactions dies upon bad sql";
            }


            function testTrans3()
            {
                $tbl = $this->tbl;

                //--- test that you can rollback a transaction
                $this->conn->StartTrans();
                $val = "Hello World at guid" . uniqid();
                $id = $this->conn->InsertAsr($tbl, array('val1' => $val), $this->IdName);

                //--- GetVal
                // Get what you just put in
                $valFromDb = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
                $this->assertTrue($val == $valFromDb);

                $this->conn->FailTrans();
                $this->conn->CompleteTrans();
                $valFromDb = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id", false);// false to not die on failure
                $this->assertTrue($val != $valFromDb, "I rolled back id $id.  But I still got the value I put in ($val) came right back out ($valFromDb) of the db after rolloback, I shouldn't have been able to get data back.  You transaction rollback didn't work");

                $this->PureComment = "test that you can manually rollback a transaction";
            }

            function testInsertAsrGetAsr()
            {
                $tbl = $this->tbl;

                $AsrIn = array('val1' => 1, 'val3' => 3, 'val2' => 2);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $AsrOut = $this->conn->GetAsr("SELECT val1, val2, val3 FROM $tbl WHERE id = $id");
                $this->assertTrue(count(array_diff($AsrIn, $AsrOut)) == 0, "The arrays don't match");
                #$this->assertTrue(,"");
                $this->PureComment = "Does insert asr and GetAsr Work (inserted id $id)";
            }


            function testGetAsr()
            {
                $tbl = $this->tbl;
                $AsrOut = $this->conn->GetAsr("SELECT val1, val2, val3 FROM $tbl WHERE 1 = 2");// there can not be a match
                $this->assertTrue(is_array($AsrOut), "I didn't get an array back");
                $this->assertTrue(count($AsrOut) == 0, "It wasn't epty");

                //Ensure dies on bad sql
                $BOrAsrOut = $this->conn->GetAsr("SELECTasdfasdfasdfsdf val1, val2, val3 FROM $tbl WHERE 1 = 2", False);// there can not be a match
                $this->assertTrue($BOrAsrOut === False, "I should have gotten an error msg");
                $Msg = $this->conn->ObjAdo->errormsg();
                $this->assertTrue(strlen($Msg) > 0, "I should have gotten a meaningful msg");
                $this->PureComment = "Test that a non-match returns an empty array.  Also verifying error reporting.  The error is $Msg";

            }


            function testGetAsr2()
            {
                $tbl = $this->tbl;
                $AsrIn = array('val1' => 1, 'val3' => 3, 'val2' => 2);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $limit = 5;
                $sql = "SELECT val1, val2, val3 FROM $tbl LIMIT $limit";
                $AsrOut = $this->conn->GetAsr($sql);
                $this->assertTrue(is_array($AsrOut));
                $this->assertTrue(count($AsrOut) == 3, "sql=$sql \n (count(AsrOut) = " . (count($AsrOut)));//v1v2v3
                foreach (array_keys($AsrOut) as $key) {
                    $this->assertTrue(!(is_array($AsrOut[$key])), "I shouldn't be finding arrays inside the outer array - are you sure you are getting what you expect");
                }
                #$this->assertTrue(count($AsrOut)==1,"I wasn only supposed to get 1 row back, I got ".count($AsrOut)." The sql was $sql.  My returned array was ".EtStringConvert::var_export2pure($AsrOut));
                $this->PureComment = "Test that multiple matches for GetAsr that it only returns the first row";
            }

            function testVal1()
            {
                $tbl = $this->tbl;
                $valOrB = $this->conn->GetVal("SELECT val1, val2, val3 FROM $tbl WHERE 1 = 2", False);
                $this->assertTrue($valOrB === False, "val='$valOrB' " . (($valOrB == False) ? 'False' : 'True') . "  type=" . gettype($valOrB));


                //Ensure this this fails loudly
                $BOrAsrOut = $this->conn->GetVal("SELECTasdfasdfasdfsdf val1, val2, val3 FROM $tbl WHERE 1 = 2", False);// there can not be a match
                $this->assertTrue($BOrAsrOut === False, "I should have gotten an error msg");
                $Msg = $this->conn->ObjAdo->errormsg();
                $this->assertTrue(strlen($Msg) > 0, "I should have gotten a meaningful msg");
                $this->PureComment = "I get nothing, not even null if I don't match.  Also verifying error reporting.  The error is $Msg";

            }


            function testAsrNull()
            {
                $tbl = $this->tbl;
                $AsrIn = array('val1' => 1, 'val2' => NULL, 'val3' => 2);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $AsrOut = $this->conn->GetAsr("SELECT val1, val2, val3 FROM $tbl WHERE id = $id");
                $this->assertTrue($AsrOut['val1'] == '1', "the insert didn't happen");
                $this->assertTrue($AsrOut['val3'] == '2', "the insert didn't happen");
                $this->assertTrue(is_null($AsrOut['val2']), "I expect a null");

                $this->PureComment = "I'm testing that if I InsertAsr with an Null as a field, then it gets properly inserted. id = $id.  Fix this via ClsStringConvert  line 429(public static function asr2sql_inser";
            }


            function testGetVal2()
            {
                $tbl = $this->tbl;
                // put in dummy data
                $val = $Input = "Hello World at guid" . uniqid();
                $id = $this->conn->InsertAsr($tbl, array('val1' => $val), $this->IdName);

                //--- GetVal
                // Get what you just put in
                $valFromDb = $GeneratedOutput = $this->conn->GetVal("SELECT val1 FROM $tbl WHERE id = $id");
                $this->assertTrue($val == $GeneratedOutput, "id = $id, Input=$Input, Actual=>$GeneratedOutput.  They should be the same");
                $this->PureComment = "Simplest getVal";
            }


            function testInsertGetSpecialCharacters()
            {
                $tbl = $this->tbl;
                $AsrIn = array('val1' => 'hi', 'val2' => '"hi"', 'val3' => '"hi');
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $AsrOut = $this->conn->GetAsr("SELECT val1, val2, val3 FROM $tbl WHERE id = $id");
                $this->assertTrue($AsrOut['val1'] == 'hi', "the insert didn't happen");
                $this->assertTrue($AsrOut['val2'] == '"hi"', "the insert didn't happen");
                $this->assertTrue($AsrOut['val3'] == '"hi', "the insert didn't happen");

                $AsrIn = array('val1' => "'hi", 'val2' => "'hi''\'", 'val3' => "'hi''\''");
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $AsrOut = $this->conn->GetAsr("SELECT val1, val2, val3 FROM $tbl WHERE id = $id");
                @$this->assertTrue($AsrOut['val1'] == "'hi", "the insert didn't happen");
                @$this->assertTrue($AsrOut['val2'] == "'hi''\'", "the insert didn't happen");
                @$this->assertTrue($AsrOut['val3'] == "'hi''\''", "the insert didn't happen");

                $this->PureComment = "Test the strings with ticks or quotes don't cause problems";
            }

            function testUpdate()
            {
                $tbl = $this->tbl;
                $n = rand(1, 10 * $this->XFactor);
                for ($i = 0; $i < $n; $i = $i + 1) {
                    // put in dummy data
                    $val = $Input = "Hello World at guid" . uniqid();
                    $AsrIn = array('val1' => 'hi', 'val2' => '"hi"', 'val3' => '"hi', 'val4' => $val);
                    $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                    $AsrOut = $this->conn->GetAsr("SELECT val1, val2, val4, val3 FROM $tbl WHERE id = $id");
                    $this->assertTrue($AsrOut['val1'] == 'hi', "the insert didn't happen");
                    $this->assertTrue($AsrOut['val2'] == '"hi"', "the insert didn't happen");
                    $this->assertTrue($AsrOut['val3'] == '"hi', "the insert didn't happen");
                    $this->assertTrue($AsrOut['val4'] == $val, "the insert didn't happen");

                    //-- the update
                    $valp = $Input = "Hello World at guid" . uniqid();
                    $this->conn->UpdateAsr($tbl, array('val4' => $valp), "id = $id");
                    $Out = $this->conn->GetVal("SELECT val4 FROM $tbl WHERE id = $id");
                    $this->assertTrue($Out == $valp, "the update didn't happen");

                    // check that only that one record got updated (warning: slow)
                    $count = $this->conn->GetVal("SELECT count(*) as v FROM $tbl WHERE val4='$valp'");
                    $this->assertTrue($count == 1, "too many things got updated.  I expected 1, but got $count");

                }

                // Test fails on bad where clause sql
                $BFailOrId = $this->conn->UpdateAsr($tbl, $AsrIn, "bob = 5 AND LASKJDF ;LKJSA DFOJK ASDLFKJ;LJ K", False);
                $this->assertTrue($BFailOrId === False, "This should have failed because of bad sql where clause");
                $Msg = $this->conn->ObjAdo->ErrorMsg();
                $this->assertTrue(strlen($Msg) > 0);

                $this->PureComment = "Test some random inserts and subsequent updates.  The error msg = $Msg";
            }

            function testGetAsr_NoMatch()
            {
                $tbl = $this->tbl;
                $AsrIn = array('val1' => 1, 'val3' => 3, 'val2' => 2);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);

                $limit = 5;
                $sql = "SELECT val1, val2, val3 FROM $tbl where 1=2";
                $AsrOut = $this->conn->GetAsr($sql);
                $this->assertTrue(is_array($AsrOut));
                $this->assertTrue(count($AsrOut) == 0, "sql=$sql \n (count(AsrOut) = " . (count($AsrOut)));
                $this->PureComment = "test that if the sql doesn't match anything, that you get an empty array.";
                #@TODO look into what depens on this getting real results in db.php and do some error checking.
            }


        function testGetTables()
        {
            $GJjrDbConn = GfdbConfig::$GJjrDbConn;
            $tbl = $this->tbl;
            $ArrTables = $GJjrDbConn->GetTables();
            $this->assertTrue(in_array($tbl, $ArrTables), "I couldn't fine $tbl");
            $this->PureComment = "Test that GetTables does actually seem to get a list of tables.  I looked for, and found '$tbl'";
        }

        function testErrorMessages()
        {
            $tbl = $this->tbl;

            $BRet = $this->conn->Execute("SELECsadfs * FROM $tbl blah oh really bad sql here - i will die.", array('BDieOnFailure' => False));
            $this->assertTrue($BRet == False);
            $StrMsg = $this->conn->ObjAdo->ErrorMsg();
            $this->assertTrue(strlen($StrMsg) > 0, "Msg=$StrMsg");
            $this->PureComment = "show that bad sql doesn't die silently - it should scream, espcecially development.  The actual error was: $StrMsg";

            // Test inserting non-existant fields dies
            $Asr = array('val1' => 'hi', 'val2' => 'bye', 'pretentfield' => 'pain');
            $Actual = $BSuccessOrId = $this->conn->InsertAsr($tbl, $Asr, 'Id', False);
            $Expected = False;
            $this->AssertTrue($Actual === $Expected);


        }

        function testGetArrAsr()
        {
            #$this->PureComment = "Like GetArrAsr, but each row is keyed to the specified column, like Id";
            $this->PureComment = '';
            $tbl = $this->tbl;
            $AsrIn = array('val1' => 1, 'val3' => 3, 'val2' => 2, 'val4' => 'MyId');
            $ArrAsrIn = array();

            for ($i = 1; $i <= 10; $i++) {
                $AsrIn['val4'] = $i;
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $ArrAsrIn[] = $AsrIn;
            }

            $AsrAsrOut = $this->conn->GetArrAsr("SELECT * FROM $tbl order by val4 asc");
    //
    //	    	#print_r($AsrAsrOut);
    //	    	// partial checking
    //	    	foreach ($AsrAsrOut as $Row=>$AsrOut) {
    //				foreach (array_keys($AsrOut) as $Keyout ) {
    //					$Actual = in_array($KeyIout,array_keys($AsrAsrIn[$Row])) ;
    //		    		$this->assertTrue($Actual==True, "I'm missing the Key=>$Key");
    //		    		foreach ($ArrAsrIn[$Row] as $KeyIn=>$AsrIn) {
    //		    			$Actual = in_array($Input,$AsrAsrOut[$Key]);
    //		    			$this->assertTrue($Actual==True, "I'm missing the val=>$Input");
    //		    		}
    //				}
    //	    	}
        }

        function testGetAsrAsr()
        {
            $this->PureComment = "Like GetArrAsr, but each row is keyed to the specified column, like Id";
            $tbl = $this->tbl;
            $AsrIn = array('val1' => 1, 'val3' => 3, 'val2' => 2, 'val4' => 'MyId');
            $ArrAsrIn = array();
            for ($i = 1; $i <= 10; $i++) {
                $AsrIn['val4'] = $i * $i;
                $id = $this->conn->InsertAsr($tbl, $AsrIn, $this->IdName);
                $ArrAsrIn[] = $AsrIn;
            }

            $AsrAsrOut = $this->conn->GetAsrAsr("SELECT * FROM $tbl", 'val4');
    //	print_r($AsrAsrOut);
    //	    	// check the
    //	    	foreach ($ArrAsrIn as $Row=>$AsrIn) {
    //				foreach (array_keys($AsrIn) as $KeyIn ) {
    //					$Actual = in_array($KeyIn,array_keys($AsrAsrOut[0])) ;
    //		    		$this->assertTrue($Actual==$Expected, "I'm missing the Key=>$Key");
    //		    		foreach ($ArrAsrIn[$KeyIn] as $AsrIn) {
    //		    			$Expected = True;
    //		    			$Actual = in_array($Input,$AsrAsrOut[$Key]);
    //		    			$this->assertTrue($Actual==$Expected, "I'm missing the val=>$Input");
    //		    		}
    //				}
    //	    	}

            foreach ($AsrAsrOut as $Key => $AsrOut) {
                $this->assertTrue($Key == $AsrOut['val4']);
            }

        }

        function testIdAutoIncrementWithSequenceTable()
        {
            global $dsnStr;
            $tbl = 'test' . uniqid();
            $this->tbls[] = $tbl;
            $this->conn = new JjrClsDb($dsnStr);//mysql://root:pwd@localhost/mydb?persist
            $this->conn->debug = true;
            $FieldId = $this->IdName = 'Id2';
            $this->conn->Execute("CREATE TABLE `$tbl` (
                      `$FieldId` int(11) NOT NULL,
                      `val1` varchar(255) default NULL,
                      `val2` varchar(255) default NULL,
                      `val3` varchar(255) NOT NULL,
                      `val4` varchar(255) NOT NULL,
                      `val5` varchar(255) NOT NULL,
                      UNIQUE KEY `$FieldId` (`$FieldId`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

            $Id = $this->conn->InsertAsr($tbl, array('val1' => 'hello'), $FieldId);
            $expected = 101;
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");
            $expected++;
            $Id = $this->conn->InsertAsr($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");
            $expected++;

            $Id = $this->conn->InsertAsr($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");
            $expected++;

            $Id = $this->conn->InsertAsr($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");
            $expected++;

            $Id = $this->conn->InsertAsr($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");
            $expected++;

            $this->PureComment = "Test that I can still increment id even when it isn't the literal string 'id' and using squence table AND checkign results";

        }


        function testIdAutoIncrementWithOutSequenceTable()
        {
            global $dsnStr;
            $tbl = 'test' . uniqid();
            //$this->tbls[] = $tbl;
            $this->conn = new JjrClsDb($dsnStr);//mysql://root:pwd@localhost/mydb?persist
            $this->conn->debug = true;
            $FieldId = $this->IdName = 'Id2';
            $autoincrementStartNumber = rand(0, 1000);
            $this->conn->Execute("CREATE TABLE `$tbl` (
                      `$FieldId` int(11) NOT NULL AUTO_INCREMENT,
                      `val1` varchar(255) default NULL,
                      `val2` varchar(255) default NULL,
                      `val3` varchar(255) NOT NULL,
                      `val4` varchar(255) NOT NULL,
                      `val5` varchar(255) NOT NULL,
                      PRIMARY KEY (`$FieldId`),
                      UNIQUE KEY `$FieldId` (`$FieldId`)
                    ) ENGINE=MyISAM AUTO_INCREMENT=$autoincrementStartNumber DEFAULT CHARSET=utf8");

            $expected = $autoincrementStartNumber;

            if ($autoincrementStartNumber === 0) { // special case when we start at zero, first id is zero
                $expected++;
            }


            $Id = $this->conn->InsertAsrIntoAutoincrement($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");

            $expected++;
            $Id = $this->conn->InsertAsrIntoAutoincrement($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");

            $expected++;
            $Id = $this->conn->InsertAsrIntoAutoincrement($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");

            $expected++;
            $Id = $this->conn->InsertAsrIntoAutoincrement($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");

            $expected++;
            $Id = $this->conn->InsertAsrIntoAutoincrement($tbl, array('val1' => 'hello'), $FieldId);
            $this->assertTrue($Id == $expected, "id=$Id , expected=>$expected");

            $this->PureComment = "Test (table='$tbl' that I can still increment id even when it isn't the literal string 'id' and using DB's autoincrement";

        }*/
}
