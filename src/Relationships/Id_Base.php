<?php
declare(strict_types=1);

namespace Gfd\Relationships;

use Gfd\Core\GfValid;
use Gfd\Core\GfValid_Provider_interface;

abstract class Id_Base implements GfValid_Provider_interface
{
    public string $id;

    public abstract static function Id_Validates(string $strIdBadMaybe): GfValid;

    public abstract static function Construct_fromNothing(): self;

    static function Construct_fromString(string $name): self
    {
        $meName = get_called_class();
        $objId = new $meName();
        $objId->id = $name;
        return $objId;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function getValidity(): GfValid {
        return static::Id_Validates($this->id);
    }
}