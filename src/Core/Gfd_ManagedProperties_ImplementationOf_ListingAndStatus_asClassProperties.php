<?php
declare(strict_types=1);
namespace Gfd\Core;


trait Gfd_ManagedProperties_ImplementationOf_ListingAndStatus_asClassProperties{
    public static function GetManagedPropertyNames(): array {
        return Gfd_ManagedProperties_Listing_meets_classReflections_Stm::GetPublicProperties_forClass(get_called_class());
    }
    public static function IsSpecifiedName_thatOfAManagedProperty(string $nameOfManagedProperty): bool
    {
        return Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofNamedClass($nameOfManagedProperty, get_called_class());
    }
    public function getManagedPropertiesThatAreSet_butExcludeNullValues(): array {
        return Gfd_ManagedProperties_Listing_meets_classReflections_Stm::GetPublicPropertiesThatHaveHaveNonNullValues_ofNamedClass($this);
    }

    /**
     * @throws \ReflectionException
     */
    public function getManagedPropertiesThatAreNotYetSet(): array {
        return Gfd_ManagedProperties_Listing_meets_classReflections_Stm::GetPublicPropertiesThatAreNotYetSet_forClass($this);
    }

    /**
     * @throws \ReflectionException
     */
    public function getManagedPropertiesThatAreSet_butIncludeNullValues(): array {
        return Gfd_ManagedProperties_Listing_meets_classReflections_Stm::GetPublicPropertiesThatAreSet_forClass($this);
    }


}