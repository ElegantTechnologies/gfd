<?php
declare(strict_types=1);
namespace Gfd\Core;

interface Gfd_ManagedProperties_ListingProvider_Interface
{
    public static function GetManagedPropertyNames(): array;
    public static function IsSpecifiedName_thatOfAManagedProperty(string $nameOfManagedProperty): bool;
    /** @returns array[string] = string */
    public static function GetManagedPropertyNames2Types(): array;

}