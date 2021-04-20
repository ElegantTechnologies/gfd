<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once (__DIR__.'/JDbTester_Implementation.php');

final class TestDb_010_HelloWorldTest extends TestCase
{
    use JDbTester_Implementation;
#https://stackoverflow.com/questions/4585345/phpunit-testing-with-database
#https://phpunit.de/manual/6.5/en/database.html
    function testTouchDb()
    {
        $gdb = \Gfd\Db\Gfdb::One();
        $tbl = $this->getWipTableName();
        $this->assertTrue(true, "1 " . __LINE__);
        $arr = $gdb->getAsr("SELECT * FROM $tbl;");
        $this->assertTrue(true, "1 " . __LINE__);
    }
}