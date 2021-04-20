<?php

namespace Gfd\Relationships;



Class Foreign_toUlid extends \SchoolTwist\Cfd\Core\CfdBase implements \SchoolTwistWip\Cfd\Cfdb\Core\ForeignKey_Interface
{
    public CONST ALLOW_UPCONVERT_TYPES = ['string'];// OVERRIDE ME IF YOU DON"T WANT THESE
    /** @var string */
    public string $Value;

    public static $ForeignTableShortName = 'abstract';

    public static $ForeignKeyLongName = 'abstract';

    #public static $canBeNull = 'abstract'; hmm not a bad idea....

    public static $_ForeignLongTableName;
    protected static $_canBeNull = 'abstract';


    public static function getSqlCreateColumn(bool $canBeNull, bool $hasDefault, $default = null): \SchoolTwistWip\Cfd\Cfdb\Core\CfdSqlCreateCol
    {
        return new \SchoolTwistWip\Cfd\Cfdb\Core\CfdSqlCreateCol([
            'OriginalPropertyName' => 'Ulid',
            'SqlType' => "char(26)",
            'Comment' => "Points to ".static::$ForeignTableShortName."->".static::$ForeignKeyLongName,
            #'IsUnique' => false,
//            'hasDefault' => (static::$_canBeNull) ? true : false,
//            'Default' => (static::$_canBeNull) ? NULL : 'n/a',
//            'canBeNull' => true,
            'canBeNull' => $canBeNull,
            'hasDefault' => $hasDefault,
            'Default' => $default,
        ]);
    }

    public static function dbStringCastBack($stringStraigtFromDb)
    {
        return $stringStraigtFromDb;
    }

    public static function Value_Validates($candidateValue) : \SchoolTwist\Validations\Returns\DtoValid
    {
        return Ulid::IdString_Validates($candidateValue);
    }

    public function __toString()
    {
        return $this->Value.'';
    }

    public static function getForeignTableName_Long() : string
    {
        return \SchoolTwistWip\Cfd\Cfdb\Core\CfdbTblBase::calculateLongTableName(static::$ForeignTableShortName);
    }

    public static function getForeignKeyName() : string
    {
        return static::$ForeignKeyLongName;
    }

    public static function from_string(string $valueAsString, bool $assumeValidValue): self {
        if (!$assumeValidValue) {
            assert(static::Value_Validates($valueAsString)->isValid);
        }

        $myName = get_called_class();
        $newMe = new $myName(['Value'=>$valueAsString]);
        return $newMe;

    }

}
