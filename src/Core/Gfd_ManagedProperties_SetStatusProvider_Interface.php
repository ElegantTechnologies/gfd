<?php
declare(strict_types=1);
namespace Gfd\Core;

interface Gfd_ManagedProperties_SetStatusProvider_Interface
{
    public function getManagedPropertiesThatAreSet_butExcludeNullValues(): array;
    public function getManagedPropertiesThatAreSet_butIncludeNullValues(): array;
    public function getManagedPropertiesThatAreNotYetSet(): array;
}