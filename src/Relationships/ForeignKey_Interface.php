<?php

namespace Gfd\Relationships;

interface ForeignKey_Interface
{
    public static function getForeignTableName_Long() : string ;
    public static function getForeignKeyName() : string;
}