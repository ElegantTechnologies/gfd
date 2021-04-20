<?php
declare(strict_types=1);

namespace Gfd\Db;

use PDOStatement;

trait Gfdb_shortcuts_implementation {
    /** var Gfdb $this */


    /* A smidge faster than getAsrByKey */
    public function getAsrByFirst(string $sql_firstSelectParamIsAsrKey): array
    {
        //https://www.php.net/manual/en/pdostatement.fetchall.php#88699
        /** @var \PDO $pdo */
        $pdo = $this->pdo;
        $results = $pdo->query($sql_firstSelectParamIsAsrKey)->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
        $results = array_map('reset', $results);
        return $results;
    }

    public function getAsrByKey(string $sql, string $NameOfKeyField): array
    {
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $arrAsr = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $asrAsr = [];
        foreach ($arrAsr as $asr) {
            $key = $asr[$NameOfKeyField];
            $asrAsr[$key] = $asr;
        }
        return $asrAsr;

        //        /** @var \PDO $pdo */
        //        $pdo = $this->pdo;
        //        $ret = $pdo->query($sql)->fetchAll(\PDO::FETCH_COLUMN);
        //
        //        $AsrArr = [];
        //        foreach ($ret as $Asr) {
        //            $key
        //            $AsrArr[$Asr[$NameOfKeyField]] = $Asr;
        //        }
        //        return $AsrArr;
    }
    //*** @returns array Return a list of table names
    function getTables(bool $bAlphabetize = false): array
    {
        /** @var \PDO $pdo */
        $pdo = $this->pdo;
        $arrTbls = $pdo->query("show tables")->fetchAll();
        if ($bAlphabetize == True) {
            sort($arrTbls);
        }

        return $arrTbls;
    }

    function InsertAsrIntoAutoincrement($tblName,$asr,$StrFieldIdOfPrimaryKeyToAutoIncrement, $BDieOnFailure = True){
        // ------ Let's move to wordpress -begin-
        //https://developer.wordpress.org/reference/classes/wpdb/insert/
        // ex: wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
        global $wpdb;
        $numRowsInserted = $wpdb->insert( $tblName, $asr);
        if ($numRowsInserted == 0 ) {
            if (!JjrClsArray::IsSubsetOf(array_keys($asr),$this->GetFields($tblName)) ) {
                if ($BDieOnFailure == True) {
                    $ArrAllowed = $this->GetFields($tblName);
                    $ArrSent = array_keys($asr);

                    sort($ArrAllowed);
                    sort($ArrSent);

                    $ArrDiff = array_diff($ArrSent, $ArrAllowed);
                    EtError::record('error',__FILE__,__LINE__,"There was an error in your sql.  Your fields don't match.  The allowed fields are ".EtStringConvert::print_r($ArrAllowed)." You sent values for these fields ".EtStringConvert::print_r($ArrSent).", and StrFieldId='$StrFieldIdOfPrimaryKeyToAutoIncrement'.  So,  I can't do anything with these extra fields:".EtStringConvert::print_r($ArrDiff));
                } else {
                    return false;
                }
            }


            if ($BDieOnFailure==True) {
                EtError::record('error',__FILE__,__LINE__,"There was an error in your sql in $tblName.  Here is the message: '".$wpdb->last_error ."' tblName=>$tblName,  asr=>".EtStringConvert::var_export2pure($asr));
            } else {
                return false;
            }
        } else {
            return $wpdb->insert_id;
        }
//        // ------ Let's move to wordpress -end-
//        $gUprobe = EtConfig::$gUprobe;//global $gUprobe;
//        $_ut = $_ut = $gUprobe->start("JjrClsDb::InsertAsr");//EtProbe::singleton()->start("EtDb::InsertAsr");
//        EtError::AssertTrue(!in_array($StrFieldIdOfPrimaryKeyToAutoIncrement, array_keys($asr)),'error',__FILE__,__LINE__,"Oh - total no-no.  When using InsertAsrIntoAutoincrement, you can't have the autoincrement field be in the data array - how would I know that you really knew that your where expecting things to autoincrement.  You might try unset(\$asr['$StrFieldIdOfPrimaryKeyToAutoIncrement'])");
//        $old_id = $this->GetVal("SELECT MAX($StrFieldIdOfPrimaryKeyToAutoIncrement) FROM $tblName");// Hmmm - won't this fail for new databases? Fix this once you come back here and have a real use case!
//        if (!JjrClsArray::IsSubsetOf(array_keys($asr),$this->GetFields($tblName)) || (strlen($this->ObjAdo->ErrorMsg())>0)) {
//            if ($BDieOnFailure == True) {
//                $arrExtras = array_diff(array_keys($asr),$this->GetFields($tblName));
//                $strExtras = implode(',',$arrExtras);
//                EtError::record('error',__FILE__,__LINE__,"There was an error in your sql.  Your fields don't match in $tblName.  The allowed fields are ".EtStringConvert::var_export2pure($this->GetFields($tblName))." You sent values for these fields ".EtStringConvert::var_export2pure(array_keys($asr)).", and StrFieldId='$StrFieldIdOfPrimaryKeyToAutoIncrement'.  <br>So this (these) are extra ($strExtras).");
//            } else {
//                return false;
//            }
//        }
//        $rs = $this->ObjAdo->AutoExecute($tblName,$asr,'INSERT',false,True);
//
//        if (strlen($this->ObjAdo->ErrorMsg())>0) {
//            if ($BDieOnFailure==True) {
//                EtError::record('error',__FILE__,__LINE__,"There was an error in your sql in $tblName.  Here is the message: '".$this->ObjAdo->ErrorMsg() ."' tblName=>$tblName,  asr=>".EtStringConvert::var_export2pure($asr));
//            } else {
//                return false;
//            }
//        }
//        $insertIdAccordingToAdo  = $this->ObjAdo->Insert_ID();
//        #$insertIdAccordingToAdo = 5; // force fail
//        if (!isset($insertIdAccordingToAdo) || $insertIdAccordingToAdo === 0 || $insertIdAccordingToAdo == '') {
//            // Motivation: Autoincrement stuff kept dying when moving around - this is supposed to be better?
//            // src: http://stackoverflow.com/questions/17112852/get-the-new-record-primary-key-id-from-mysql-insert-query
//            // Future: maybe only use this method
//            $insertIdAccordingToAdo = $insertIdAccordingToAdoAlt =  $this->GetVal("SELECT LAST_INSERT_ID();");
//        }
//
//        EtError::AssertTrue(isset($insertIdAccordingToAdo),'error',__FILE__,__LINE__,"Whoa - I couldnt get the id");
//        if ($BDieOnFailure) {
//            $biggestId = $this->GetVal("SELECT MAX($StrFieldIdOfPrimaryKeyToAutoIncrement) FROM $tblName");
//            EtError::AssertTrue($insertIdAccordingToAdo != $old_id,'error',__FILE__,__LINE__,"Whoa - The new id ($insertIdAccordingToAdo) is the same as the old id ($old_id) for table '$tblName' and primaryField '$StrFieldIdOfPrimaryKeyToAutoIncrement'. Also, biggestId=>'$biggestId'. I don't trust my ID.  This either happens after you've been deleting a bunch of records and the autoincrement pointer is high, or if you have a transaction issue and somebody else stuffed a record in here while we were busy, or if you moved DBs and the method for returning the autoincrement id is different (so you need to enhance this function - joy).  You could briefly modify code to ignore the error");
//        } else {
//            return $insertIdAccordingToAdo;
//        }
//
//        $gUprobe->stop("JjrClsDb::InsertAsr",$_ut);
//        return $insertIdAccordingToAdo;
    }
    /***
     * @input array associate array that is inserted into the table
     * #future note: when you restore from backup, you won't want to have auto id
     * You're Id isn't alway 'Id' =
     * @input string $$StrFieldIdOfPrimaryKeyToAutoIncrement field name of what should be autoincremented.  set to NULL if nothing will be incremented
     */
    function insertAsr($tblName,$asr,$StrFieldIdOfPrimaryKeyToAutoIncrement, $BDieOnFailure = True){
        return $this->InsertAsrIntoAutoincrement($tblName,$asr, $BDieOnFailure);



        $gUprobe = EtConfig::$gUprobe;
        $_ut = $gUprobe->start("JjrClsDb::InsertAsr");
        $id = NULL;
        if (!is_null($StrFieldIdOfPrimaryKeyToAutoIncrement)){
            $_ut2 = $gUprobe->start("JjrClsDb::InsertAsr::bUseAutoIncrementId");
//			$StrSequenceTableName = $StrPureSequenceTableName = $tblName.'Sequence';
//			if (strlen($StrPureSequenceTableName)>=63) {
//				// state: table name is too long!
//				$StrSequenceTableName = $StrEncodeSequenceTableName = strtolower(md5($tblName.$StrFieldIdOfPrimaryKeyToAutoIncrement)).'Sequence';
//				if (strlen($StrEncodeSequenceTableName)>=63) {
//					// state I couldn't shrink the table name, its still too long
//					if ($BDieOnFailure) {
//						EtError::record( 'error', __FILE__, __LINE__, "The table name for primary key is too long - I can't generate that, nore a suitably encoded version of it.  The orig=>'$StrPureSequenceTableName', my encoded version is => '$StrEncodeSequenceTableName'");
//					} else {
//						return false;
//					}
//				} else {
//					// state: I was able to shrink the table name
//				}
//			}
            $StrSequenceTableName = JjrClsDb::GenerateSequenceTableName($tblName,$StrFieldIdOfPrimaryKeyToAutoIncrement);
            $id = $this->ObjAdo->genID($StrSequenceTableName,101);//2nd parm is starting sequence number
            EtError::AssertTrue( $id != 0, 'error', __FILE__, __LINE__, "The Id I got for this insert is zero, that is bad.  I bet you can't write/update the table sequence table. Did you you just import a sql structure - if so, then make sure that the corresponding '$StrSequenceTableName' isn't in the script - it needs to be automatically generated.  If you DO NOT have data in your db, then deleting the '$StrSequenceTableName' table will re-initieate sequencing ");

            $asr[$StrFieldIdOfPrimaryKeyToAutoIncrement]=$id;
            #@NiceTODO: better handle starting at right Id when creating tables - it wants to start at '1' even if the table already exists and has higher Ids
            $gUprobe->stop("JjrClsDb::InsertAsr::bUseAutoIncrementId",$_ut2);
        }

        if (!JjrClsArray::IsSubsetOf(array_keys($asr),$this->GetFields($tblName)) || (strlen($this->ObjAdo->ErrorMsg())>0)) {
            if ($BDieOnFailure == True) {
                $ArrAllowed = $this->GetFields($tblName);
                $ArrSent = array_keys($asr);

                sort($ArrAllowed);
                sort($ArrSent);

                $ArrDiff = array_diff($ArrSent, $ArrAllowed);
                EtError::record('error',__FILE__,__LINE__,"There was an error in your sql.  Your fields don't match.  The allowed fields are ".EtStringConvert::print_r($ArrAllowed)." You sent values for these fields ".EtStringConvert::print_r($ArrSent).", and StrFieldId='$StrFieldIdOfPrimaryKeyToAutoIncrement'.  So,  I can't do anything with these extra fields:".EtStringConvert::print_r($ArrDiff));
            } else {
                return false;
            }
        }
        $rs = $this->ObjAdo->AutoExecute($tblName,$asr,'INSERT',false,True);

        if (strlen($this->ObjAdo->ErrorMsg())>0) {
            if ($BDieOnFailure==True) {
                EtError::record('error',__FILE__,__LINE__,"There was an error in your sql.  Here is the message: '".$this->ObjAdo->ErrorMsg() ."' tblName=>$tblName, WhereStub=>$WhereStub, asr=>".EtStringConvert::var_export2pure($asr));
            } else {
                return false;
            }
        }

        $gUprobe->stop("JjrClsDb::InsertAsr",$_ut);
        return $id;
    }

    /***
     * Get first row or a sql select and store in an associative array
     * If no match, then a empty array is returned
     *
     * maintainer's note: had issues getting away from Execute $query = "SELECT count(id) as num_pages FROM book_all WHERE book_id = $row[id] ORDER BY pg DESC LIMIT 0,1";
     * it might have had something to do with limiting a 'count'.  so this wasn't working
     * You'll need to think if SelectLimit makes sense here.  There might be some speed opportunities.
     *    $rs = $this->objPdo->SelectLimit($sql,1);
     *    return $rs->GetRowAssoc(false);
     *    if (!$rs->fields) {
     *        return array();
     *    } else {
     *        return $rs->fields;
     *    }
     *
     *
     */
    public function getAsr(string $sql, bool $BDieOnFailure = True): array
    {
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $asr = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $asr;

    }

    /* returns a single row */
    public function getAsrOne(string $sql, bool $BDieOnFailure = True): array
    {
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $asr = $sth->fetch(\PDO::FETCH_ASSOC);
        if ($asr === false) {
            return [];
        }
        return $asr;

    }

    // Motivation: get a bunch of records, then find one of them by its id
    // Like GetArrAsr, but each row is keyed to the specified field

    /** Get just a single value from a select statement
     * only return the first listed from a select clause, like (SELECT last_name, first_name FROM tblCustomer WHERE cust_id='5') would return "Johnson"
     * @returns value a single value from the first row of the select clause.  If no match is found, then nothing is returned.  (Test by if(!isset($ret)))
     * @author jrohrer
     * @since 2005116
     *
     *
     * Known Limitation.  Dies if ends with LIMIT
     */

    public function getVal(string $sqlWithNOLimitStatemt, bool $BDieOnFailure = True)
    {
        $sql = $sqlWithNOLimitStatemt;

        //ensure no LIMIT statement at end
        #EtError::AssertTrue(strpos(tolower( ... well, make sure you aren't search strings.
        #$gUprobe = GfdbConfig::#$gUprobe;
        $ut = #$gUprobe->start("JjrClsDb::GetVal");
        $ret = $val = $this->pdo->GetOne($sql);#Executes the SQL and returns the first field of the first row. The recordset and remaining rows are discarded for you automatically. If an error occur, false is returned.
        if (strlen($this->pdo->ErrorMsg()) > 0) {
            if ($BDieOnFailure) {
                EtError::record('error', __FILE__, __LINE__, "There was an error in your sql.  Here is the message: " . $this->pdo->ErrorMsg() . " The original sql was '$sql'");
            } else {
                return false;
            }
        }

        #$gUprobe->stop("JjrClsDb::GetVal",$ut);
        if ($val === false) {
            // State: I didn't get anything back, the programmer must have a misunderstanding of what he is getting
            if ($BDieOnFailure) {
                EtError::record('error', __FILE__, __LINE__, "I tried to GetVal, but I didn't get anything back.  I have to presume this isn't what you expected. Now, don't even consider changing the logic in GetVal,  If you don't KNOW that you will get a value back, then test first.  This method was leading to too much debugging because it 'failed' TOO gracefully.  The original sql was '$sql'");
            } else {
                return false;
            }
        } else {
            return $val;
        }
    }

    /** returns an assoicative array where the first field is the key and the second field is the value
     * @param string your sql string
     * #@param string your field name - must match exactly a field name / expression from your sql
     * @status work in progres --- not sure if this really works yet.
     * 7/15' Works, but I think it fails if nothing is returned from the first $ArrAsr = $this->GetArrAsr($sql) area
     */

    public function getKeyVal(string $sql, bool $bDieOnNoResults = true): array
    {
        $ArrAsr = $this->GetArrAsr($sql);
        if ($bDieOnNoResults && empty($ArrAsr)) {
            EtError::record('error', __FILE__, __LINE__, "You didn't get any results back from GetKeyVal($sql)");
        } else if (empty($ArrAsr)) {
            return [];
        }
        $Asr = array();
        $ArrFields = array_keys($ArrAsr[0]);
        $KeyKey = $ArrFields[0];
        $ValueKey = $ArrFields[1];
        foreach ($ArrAsr as $slot => $AsrRow) {
            $Asr[$AsrRow[$KeyKey]] = $AsrRow[$ValueKey];
        }
        return $Asr;
        $ret = $rows = $this->Execute($sql, false);
        if ($ret === false) {
            EtError::record('error', __FILE__, __LINE__, "There was an error in your sql.  Here is the message: " . $this->pdo->ErrorMsg());
        }

        if ($rows && $rows > 0) {
            while ($row = $this->_fetch_array($this->_result)) {
                $asr[$row[0]] = $row[1];
            }
            return $asr;
        } else {
            return array();
        }
    }


    public function getArrAsr(string $sql)
    {
        $ret = $this->pdo->GetAll($sql);
        if ($ret === false) {
            EtError::record('error', __FILE__, __LINE__, "There was an error in your sql.  Here is the message: " . $this->pdo->ErrorMsg());
        }
        return $ret;

    }

    public function execute(string $sql, $inputarr = false)
    {
        $BDieOnFailure = (isset($inputarr['BDieOnFailure'])) ? $inputarr['BDieOnFailure'] : True;
        $ret = $this->pdo->Execute($sql, $inputarr);
        if ($ret === false && $BDieOnFailure) {
            EtError::record('error', __FILE__, __LINE__, "There was an error in your sql.  Here is the message: " . $this->pdo->ErrorMsg());
        }
        return $ret;
    }



    /** Get an array of values.  the values are from the first field
     * @returns array an array of values from the first field returned
     * @example $arrFacility_Ids = $conn->GetCol("SELECT Facility_Id FROM tblFacilities WHERE Active = 1"); //$arrFacility_Ids = > Array('5','6,'7')
     */
    public function getArr(string $sql): array
    {
        #$gUprobe = GfdbConfig::#$gUprobe;
        #_ut = #$gUprobe->start("clsDb::GetCol");
        $ret = $this->pdo->GetCol($sql);
        if ($ret === false) {
            EtError::record('error', __FILE__, __LINE__, "There was an error in your sql.  Here is the message: " . $this->pdo->ErrorMsg());
        }

        #$gUprobe->stop("clsDb::GetCol",#_ut);
        return $ret;

    }


    /** Get an associative array of values.  the values are from the first field
     * @returns asr an array of values from all selected fields returned
     * Motivation: I wanted to select data to put into a scatter plot with a single method
     * @example <pre>
     *    include (GetIncludePath("jpgraph/src/jpgraph.php"));
     *    include (GetIncludePath("jpgraph/src/jpgraph_scatter.php"));
     *
     *    $asrData = $gConn->GetAsrCol("SELECT valx, valy FROM tblRptIt_User_Log WHERE keyword='page_visit'");
     *    #$datax = array(3.5,3.7,3,4,6.2,6,3.5,8,14,8,11.1,13.7);
     *    #$datay = array(20,22,12,13,17,20,16,19,30,31,40,43);
     *
     *    $graph = new Graph(300,200,"auto");
     *    $graph->SetScale("linlin");
     *
     *    $graph->img->SetMargin(40,40,40,40);
     *    $graph->SetShadow();
     *
     *    $graph->title->Set("Response Times by Date");
     *    $graph->title->SetFont(FF_FONT1,FS_BOLD);
     *
     *    #$sp1 = new ScatterPlot($datay,$datax);
     *    $sp1 = new ScatterPlot($asrData['valx'],$asrData['valy']);
     *
     *    $graph->Add($sp1);
     *    $graph->Stroke($image_path);
     *
     *    $html = "
     *    Hello World. <img src='$image_url'>";
     *    print $html;
     *</pre>
     */
    public function getAsrArr(string $sql): array
    {
        $asr = $this->GetArrAsr($sql);
        $asr2 = $this->asrdb2asrstat($asr);
        return $asr2;


    }


    /**
     * transpose an association array returned from JjrDbClsDb to one suitable for stats processing - derived from api_stats
     * puts the traditional array of associative arrays into that is keyed off the column header with a long
     * list of results
     * from:
     *    array (
     *        array('datax'=>3,'datay'=>20)
     *        , array('datax'=>5,'datay'=>22)
     *        , array('datax'=>4,'datay'=>12)
     *    *        ...);
     *    to:
     *    array(
     *        'datax' => array(3.5,3.7,3,4,6.2,6,3.5,8,14,8,11.1,13.7)
     *        , 'datay' => array(20,22,12,13,17,20,16,19,30,31,40,43)
     *        );
     *
     * @returns associative_array suitable for use with setData
     */
    public function asrdb2asrstat(&$asrdb): array
    {
        // transpose the array
        $arreglo_aux = array();
        foreach ($asrdb as $keymaster => $value) {
            foreach ($value as $key => $elemento) {
                $arreglo_aux[$key][$keymaster] = $elemento;
            }
        }

        // wrap-up
        return $arreglo_aux;
    }


    //Gets the names of the fields used in the specified table
    // @returns array array of field names
    public function getFields(string $tblName, bool $bAlphabetize = false): array
    {
        $arrObjField = $this->pdo->MetaColumns($tblName);
        $arrCols = array();
        EtError::AssertTrue(is_array($arrObjField), 'error', __FILE__, __LINE__, "The table '$tblName' isn't built out.");

        foreach ($arrObjField as $objField) {
            $arrCols[] = $objField->name;
        }
        if ($bAlphabetize == True) {
            sort($arrCols);
        }

        return $arrCols;
    }



    /***
     * use this to update a record in a table
     * the third argument is an required where clause
     * It is required to keep the too-easy mistake of accidental mass updates
     *
     * @returns boolean true upon success, false otherwise
     */
    public function updateAsr($tblName, $asr, $WhereStub, bool $BDieOnFailure = True): bool
    {
        #$gUprobe = GfdbConfig::#$gUprobe;
        #_ut = #$gUprobe->start("JjrClsDb::UpdateAsr");
        $bResult = $this->pdo->AutoExecute($tblName, $asr, 'UPDATE', $WhereStub, false, True);
        if (strlen($this->pdo->ErrorMsg()) > 0) {
            if ($BDieOnFailure == True) {
                EtError::record('error', __FILE__, __LINE__, "There was an error in your sql.  Here is the message: '" . $this->pdo->ErrorMsg() . "' tblName=>$tblName, WhereStub=>$WhereStub, asr=>" . EtStringConvert::var_export2pure($asr));
            } else {
                return false;
            }
        }
        return true;// 8/28/16'  Was get false negatives on updating forms - arghh //return $bResult;
    }


    public function startTransaction()
    {
        $this->pdo->StartTrans();
    }

    public function completeTransaction()
    {
        $this->pdo->CompleteTrans();
    }

    public function failTransaction()
    {
        $this->pdo->FailTrans();
    }

    public function delete($TblName, $primary_key_value, $primary_key_name = 'id', bool $BDieOnFailure = true)
    {
        $bResult = $this->pdo->Execute("DELETE FROM $TblName WHERE $primary_key_name = '$primary_key_value'");
        if (strlen($this->pdo->ErrorMsg()) > 0) {
            if ($BDieOnFailure == True) {
                EtError::record('error', __FILE__, __LINE__, "There was an error in your sql.  Here is the message: '" . $this->pdo->ErrorMsg() . "' primary_key_value=>$primary_key_value, primary_key_name=>$primary_key_name You probably want to specify the name of the primary field instead of relying upon the default 'id' - which is just there for legacy purposes");
            } else {
                return false;
            }
        }

        return $bResult;
    }

}
