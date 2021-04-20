<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;


final class TestRelationships_300_Id_Id_Test extends TestCase
{
    function testUlid()
    {
        $id_asString = '1';
        $id = \Gfd\Relationships\Id_IdAutoIncrement::Construct_fromString($id_asString);
        $this->assertTrue($id->id == $id->id);

        $id_asString = '1';
        $gfv = $id::id_validates($id_asString);
        $this->assertTrue($gfv->isValid());

        $id_asString = '999';
        $gfv = $id::id_validates($id_asString);
        $this->assertTrue($gfv->isValid());

        $id_asString = '0';
        $gfv = $id::id_validates($id_asString);
        $this->assertTrue($gfv->isValid());

        $id_asString = '1.1';
        $gfv = $id::id_validates($id_asString);
        $this->assertFalse($gfv->isValid());

        $id_asString = '-1';
        $gfv = $id::id_validates($id_asString);
        $this->assertFalse($gfv->isValid());

        $gfv = $id::id_validates('hank');
        $this->assertFalse($gfv->isValid());

        $id_asString = '1';
        $id = \Gfd\Relationships\Id_IdAutoIncrement::Construct_fromString($id_asString);
        $gfv = \Gfd\Relationships\Id_IdAutoIncrement::Id_Validates($id->id);
        $this->assertTrue($gfv->isValid());

        $gfv = \Gfd\Relationships\Id_IdAutoIncrement::Id_Validates($id->id.'oops');
        $this->assertFalse($gfv->isValid());
    }

    function testInts() {
        $id_asInt = 1;
        $id = \Gfd\Relationships\Id_IdAutoIncrement::Construct_fromString((string) $id_asInt);
        $gfv = $id::id_validates((string) $id_asInt);
        $this->assertTrue($gfv->isValid());
    }
}