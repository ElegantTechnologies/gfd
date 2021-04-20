<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;


final class TestRelationships_200_HelloWorldTest extends TestCase
{
    function testUlid()
    {
        $id = \Gfd\Relationships\Id_Uuid::Construct_fromNothing();
        $id_asString = $id->toString();
        $id = \Gfd\Relationships\Id_Uuid::Construct_fromString($id_asString);
        $this->assertTrue($id->id == $id->id);


        $gfv = $id::id_validates('hank');
        $this->assertFalse($gfv->isValid());
        
        $id = \Gfd\Relationships\Id_Uuid::Construct_fromNothing();
        $gfv = \Gfd\Relationships\Id_Uuid::Id_Validates($id->id);
        $this->assertTrue($gfv->isValid());

        $gfv = \Gfd\Relationships\Id_Uuid::Id_Validates($id->id.'oops');
        $this->assertFalse($gfv->isValid());
    }
}