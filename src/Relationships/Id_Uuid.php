<?php
declare(strict_types=1);

namespace Gfd\Relationships;

use Gfd\Core\GfValid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;


class Id_Uuid extends Id_Base
{
    public static function Construct_fromNothing(): self
    {
        $objId = new Id_Uuid();
        $objId->id= \Ramsey\Uuid\Uuid::uuid4()->toString();//https://stackoverflow.com/questions/20342058/which-uuid-version-to-use
        return $objId;
    }

    public static function Id_Validates(string $strIdBadMaybe): GfValid
    {
        try {
            Uuid::fromString($strIdBadMaybe);
            return GfValid::ConstructValid();
        } catch (InvalidUuidStringException $exception) {
            return GfValid::ConstructInvalid('Invalid Uuid string', "'$strIdBadMaybe' is not a well-formatted id because " . $exception->getMessage());
        }
    }

}