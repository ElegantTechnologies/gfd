<?php
declare(strict_types=1);
namespace Gfd\Core;

interface Gfd_ManagedPropertyInsights_Interface
{
    public static function GetRequiredProperties(): array;

    public function getRequiredPropertiesWithNonNullValues(): array;

    public function getRequiredPropertiesThatAreNotYetSet(): array;

    public function getRequiredPropertiesThatAreSet(): array;
}