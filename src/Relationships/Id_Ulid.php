<?php
declare(strict_types=1);

namespace Gfd\Relationships;

use Gfd\Core\GfValid;
use Ulid\Exception\InvalidUlidStringException;

class Id_Ulid extends Id_Base
{
    public static function Construct_fromNothing(): self
    {
        $objId = new Id_Ulid();
        $objId->id = \Ulid\Ulid::generate(true)->__toString();
        return $objId;
    }



    public static function Id_Validates(string $strIdBadMaybe): GfValid
    {
        try {
            \Ulid\Ulid::fromString($strIdBadMaybe);
            return GfValid::ConstructValid();
        } catch (InvalidUlidStringException $exception) {
            return GfValid::ConstructInvalid('Invalid ULID string', "'$strIdBadMaybe' is not a well-formatted id because " . $exception->getMessage());
        }
    }






}