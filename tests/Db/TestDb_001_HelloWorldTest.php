<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;


final class TestDb_001_HelloWorldTest extends TestCase
{

    function test_IntegerBad()
    {
        $this->assertTrue(true, "1 " . __LINE__);
    }
}