<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;


final class TestRelationships_001_HelloWorldTest extends TestCase
{

    function testIntegerBad()
    {
        $this->assertTrue(true, "1 " . __LINE__);
    }
}