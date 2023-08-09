<?php
declare(strict_types=1);
namespace Gfd\Core;


use function assert;

trait Gfd_Validations_ImplementationViaEmbeddedClassReflectionStrategy {
    public static function PrevalidateCandidates(array $scaryInputs, bool $doExpectCompleteness): GfValid {
        return Gfd_Validations_implementationViaEmbeddedClassReflectionStrategy_Stm::PrevalidateCandidates($scaryInputs, $doExpectCompleteness);
    }

    //    public static function GetRequiredProperties(): array {
    //        return Gfd_Validations_Stm::GetRequiredPublicProperties_forClass(get_called_class());
    //    }
    //
    //    public function getPropertiesWithNonNullValues(): array {
    //        return Gfd_Validations_Stm::GetPropertiesThatAreRequiredAndHaveNonNullValues_forClass($this);
    //    }
    //
    //    public function getRequiredPublicPropertiesThatAreNotYetSet(): array {
    //        return Gfd_Validations_Stm::GetRequiredPublicPropertiesThatAreNotYetSet_forClass($this);
    //    }

    /** make sure I am good, and throw exception if I'm not.
     * @ throw \Exception
     * @return self
     */
    public function assertValidated (): static {
        assert(Gfd_Validations_implementationViaEmbeddedClassReflectionStrategy_Stm::ValidateObj($this)->isValid());
        return $this;
    }
    /** make sure I am good
     */
    public function getValidity (): GfValid {
        return Gfd_Validations_implementationViaEmbeddedClassReflectionStrategy_Stm::ValidateObj($this);
    }
}
