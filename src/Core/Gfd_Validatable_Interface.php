<?php
declare(strict_types=1);
namespace Gfd\Core;

interface Gfd_Validatable_Interface extends Gfd_ManagedProperties_ListingProvider_Interface
{
    public static function PrevalidateCandidates(array $scaryInputs, bool $doExpectCompleteness): GfValid;
    public static function GetManagedPropertyNames(): array;

    public static function GetManagedPropertyDefaults(): array;

     public static function GetManagedPropertyNames2Types(): array;

}