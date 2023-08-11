<?php
declare(strict_types=1);
namespace Gfd\Core;


use Exception;
use TypeError;

final class Gfd_Validations_Stm {
    /**
     * @param array  $scaryInputs
     * @param  Gfd_PropertyInsights_Interface $className
     */
    public static function PrevalidateCandidates_forClass(array $scaryInputs, string $className, bool $doExpectCompleteness): GfValid {
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

    public static function ensureNoMissingProperties(array $arrCandidatePropertyNames, $classNameOrObject): GfValid {
        $arrManagedPropertyNames = Gfd_PropertyInsights_Stm::GetPublicProperties_forClass($classNameOrObject);
        $missingFields = array_diff($arrManagedPropertyNames, $arrCandidatePropertyNames);
        if (count($missingFields) > 0 ) {
            $gfv = GfValid::ConstructInvalid('missingFields');
            $gfv->setOffendingValues($missingFields);
            return $gfv;
        }
        return GfValid::ConstructValid();
    }

    public static function PrevalidateKeyVal(string $key, $val, string $className): GfValid {
        if (! Gfd_PropertyInsights_Stm::IsManagedProperty_ofClassName($key, $className)) {
            $v = GfValid::ConstructInvalid('extraField');
            $v->setOffendingValues([$key]);
            return $v;
        }

        // type good
        try {
            $aMe = new $className();
            $aMe->$key = $val;
        } catch (TypeError $e) {
            $keyType = Gfd_PropertyInsights_Stm::GetExpectTypeOfManagedProperty_forClass($key, $className);
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

    public static function ValidateObj (object $classObject): GfValid {
        $asrKeyVal = Gfd_PropertyInsights_Stm::PresentPublicPropertiesAsStoopidArray($classObject);
        return static::PrevalidateCandidates_forClass($asrKeyVal, get_class($classObject), true);
    }

    /* make sure I am good, and throw exception if I'm not.
    */
    /** @ throw \Exception
     *
     * @throws Exception
     */
    public static function assertValidated (object $classObject): object {
        // any unset properties? That would be bad.
        $arrMissing = Gfd_PropertyInsights_Stm::GetPublicPropertiesThatAreNotYetSet_forClass($classObject);
        if (count($arrMissing) > 0) {
            throw(new Exception());
        }

        $asrkv = Gfd_PropertyInsights_Stm::PresentPublicPropertiesAsStoopidArray($classObject);
        foreach ($asrkv as $k=>$v) {
            $gfv = static::PrevalidateKeyVal($k, $v, $classObject::class);
            if (! $gfv->isValid()) {
                throw(new Exception());
            }
        }

        return $classObject;
    }
}
