<?php
declare(strict_types=1);

namespace Gfd\Relationships;

use Gfd\Core\GfValid;
use Ramsey\Uuid\Uuid;
use Ulid\Exception\InvalidUlidStringException;
use function PHPUnit\Framework\assertTrue;

// Strictly mysql ids
class Id_IdAutoIncrement extends Id_Base
{
    public static function Construct_fromNothing(): self
    {
        assert(0, 'We can not create int ids from nothing. ');
        die(-987654);
    }

    public static function Id_Validates(string $strIdBadMaybe): GfValid
    {
        return GfValid::ConstructFromBool(NumberValidationUtils::IsWhole($strIdBadMaybe), 'NotAWholeNumber');
    }
}