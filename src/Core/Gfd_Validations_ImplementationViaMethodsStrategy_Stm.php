<?php
declare(strict_types=1);
namespace Gfd\Core;


use function assert;

trait Gfd_Validations_ImplementationViaMethodsStrategy_Stm {
    public static function PrevalidateCandidates(array $scaryInputs, bool $doExpectCompleteness): GfValid {
        return Gfd_Validable_implementationViaEmbeddedClassReflectionStrategy_Stm::PrevalidateCandidates($scaryInputs, $doExpectCompleteness);
    }

    /** make sure I am good, and throw exception if I'm not.
     * @ throw \Exception
     * @return self
     */
    public function assertValidated (): static {
        assert(Gfd_Validable_implementationViaEmbeddedClassReflectionStrategy_Stm::ValidateObj($this)->isValid());
        return $this;
    }
    /** make sure I am good
     */
    public function getValidity (): GfValid {
        return Gfd_Validable_implementationViaEmbeddedClassReflectionStrategy_Stm::ValidateObj($this);
    }
}
