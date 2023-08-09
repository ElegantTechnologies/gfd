<?php
declare(strict_types=1);
namespace Gfd\Core;


use Exception;
use TypeError;

class T implements Gfd_Validations_Interface
{
    use Gfd_Validations_implementationViaEmbeddedClassReflectionStrategy_Stm;

}
trait Gfd_Validations_implementationViaEmbeddedClassReflectionStrategy_Stm {
    public static function PrevalidateCandidates(array $scaryInputs, bool $doExpectCompleteness): GfValid {
        return self::PrevalidateCandidates_forClass($scaryInputs, get_called_class(), $doExpectCompleteness);
    }

    public function getValidity(): GfValid
    {
        return self::ValidateObj($this);
    }
    private static function ValidateObj (object $classObject): GfValid {
        $asrKeyVal = Gfd_ManagedProperties_Listing_meets_classReflections_Stm::PresentPublicPropertiesAsStoopidKeyValArray($classObject);
        return static::PrevalidateCandidates_forClass($asrKeyVal, get_class($classObject), true);
    }

    /**
     * @param array  $scaryInputs
     * @param  Gfd_ManagedProperties_SetStatusProvider_Interface $className
     */
    private static function PrevalidateCandidates_forClass(array $scaryInputs, string $className, bool $doExpectCompleteness): GfValid {
        foreach ($scaryInputs as $key=>$val) {
            $gfv =  static::PrevalidateKeyVal($key,$val, $className);
            if (! $gfv->isValid()) {
                return $gfv;
            }
        }

        if ($doExpectCompleteness) {
            $gfv = static::ensureNoMissingProperties(array_keys($scaryInputs), $className);
            if (! $gfv->isValid()) {
                return $gfv;
            }
        }


        return GfValid::ConstructValid();
    }

    /** @param Gfd_ManagedProperties_ListingProvider_Interface $classNameOrObject */
    private static function ensureNoMissingProperties(array $arrCandidatePropertyNames, $classNameOrObject): GfValid {
        assert(in_array(Gfd_ManagedProperties_ListingProvider_Interface::class, class_implements($classNameOrObject)));
        $arrManagedPropertyNames = $classNameOrObject::GetManagedPropertyNames();
        $missingFields = array_diff($arrManagedPropertyNames, $arrCandidatePropertyNames);
        if (count($missingFields) > 0 ) {
            $gfv = GfValid::ConstructInvalid('missingFields');
            $gfv->setOffendingValues($missingFields);
            return $gfv;
        }
        return GfValid::ConstructValid();
    }

    private static function PrevalidateKeyVal(string $key, $val, string $className): GfValid {
        if (! Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofNamedClass($key, $className)) {
            $v = GfValid::ConstructInvalid('extraField');
            $v->setOffendingValues([$key]);
            return $v;
        }

        // type good
        try {
            $aMe = new $className();
            $aMe->$key = $val;
        } catch (TypeError $e) {
            $keyType = Gfd_ManagedProperties_Listing_meets_classReflections_Stm::GetExpectTypeOfNamedManagedProperty_forClass($key, $className);
            $typeVal = gettype($val);
            $v = GfValid::ConstructInvalid('badType', "The managed property '{$key}' expects an '{$keyType}', but but got a '$typeVal'");
            $v->setOffendingValues([$key]);
            return $v;
        }

        // functionally good
        $methodNameThatWouldStaticallyValidate = "{$key}_validates";
        if (method_exists($className,$methodNameThatWouldStaticallyValidate)) {
            /** @var GfValid $gfv */
            $gfv = $className::$methodNameThatWouldStaticallyValidate($val);
            $gfv->setOffendingValues([$key]);
            if (! $gfv->isValid()) {
                return $gfv;
            }
        }
        return GfValid::ConstructValid();
    }


    /* make sure I am good, and throw exception if I'm not.
    */
    /** @ throw \Exception
     *
     * @throws Exception
     */
    private static function assertValidated (object $classObject): object {
        // any unset properties? That would be bad.
        $arrMissing = Gfd_ManagedProperties_Listing_meets_classReflections_Stm::GetPublicPropertiesThatAreNotYetSet_forClass($classObject);
        if (count($arrMissing) > 0) {
            throw(new Exception());
        }

        $asrkv = Gfd_ManagedProperties_Listing_meets_classReflections_Stm::PresentPublicPropertiesAsStoopidKeyValArray($classObject);
        foreach ($asrkv as $k=>$v) {
            $gfv = static::PrevalidateKeyVal($k, $v, $classObject::class);
            if (! $gfv->isValid()) {
                throw(new Exception());
            }
        }

        return $classObject;
    }
}
