<?php
declare(strict_types=1);
namespace Gfd\Core;


trait Gfd_PropertyInsights_Implementation {
    public static function GetRequiredProperties(): array {
        return Gfd_PropertyInsights_Stm::GetPublicProperties_forClass(get_called_class());
    }

    public function getRequiredPropertiesWithNonNullValues(): array {
        return Gfd_PropertyInsights_Stm::GetPublicPropertiesThatHaveHaveNonNullValues_forClass($this);
    }

    public function getRequiredPropertiesThatAreNotYetSet(): array {
        return Gfd_PropertyInsights_Stm::GetPublicPropertiesThatAreNotYetSet_forClass($this);
    }

    public function getRequiredPropertiesThatAreSet(): array {
        return Gfd_PropertyInsights_Stm::GetPublicPropertiesThatAreSet_forClass($this);
    }


}