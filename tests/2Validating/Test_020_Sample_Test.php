<?php
declare(strict_types=1);

namespace TestWorld;

use Gfd\Core\Gfd_Validatable_Interface;
use Gfd\Core\Gfd_Validations_ImplementationViaMethodsStrategy_Stm;
use Gfd\Core\Gfd_ManagedProperties_SetStatusProvider_Interface;
use Gfd\Core\GfValid;
use PHPUnit\Framework\TestCase;
use Gfd\Core\Gfd_SimpleInits_Interface;
use Gfd\Core\Gfd_SimpleInits_Implementation;

class MethodTester1 extends GfdMethodBase {

     public static function GetManagedPropertyNames(): array
    {
        return ['var1', 'var2'];
    }
     public static function GetManagedPropertyDefaults(): array
    {
        return [
            'var2' => -1,
        ];
    }
      public static function GetManagedPropertyNames2Types(): array
    {
        return [
            'var1' =>   'string',
            'var2'=>    'int'
        ];
    }
}
abstract class GfdMethodBase implements Gfd_Validatable_Interface
{

    abstract public static function GetManagedPropertyNames(): array;

    abstract public static function GetManagedPropertyDefaults(): array;

    abstract  public static function GetManagedPropertyNames2Types(): array;



    public static function GetManagedPropertiesThatHaveDefaults(): array {
        return array_keys(static::GetManagedPropertyDefaults());
    }


    public static function IsSpecifiedName_thatOfAManagedProperty(string $nameOfManagedProperty): bool
    {
        return in_array($nameOfManagedProperty, static::GetManagedPropertyNames());
    }

    /** @param array<string> */
    protected static function EnsureNoMissingProperties(array $varNames) {
        $arrRequiredPublic = static::GetManagedPropertyNames();
        //$nonNullProperties = self::GetPublicPropertiesThatHaveHaveNonNullValues_ofNamedClass($objectOrClassName);
        $arrPropertiesDefaults = static::GetManagedPropertiesThatHaveDefaults();
        //$arrMissing = array_diff($arrRequiredPublic, $nonNullProperties, array_keys($asrPropertiesDefaults));
        $arrMissing = array_diff($arrRequiredPublic, $arrPropertiesDefaults);
        if (count($arrMissing) > 0) {
            $v = GfValid::ConstructInvalid('Missing Values');
            $v->setOffendingValues($arrMissing);
            return $v;
        }
        return GfValid::ConstructValid();
    }


    public static function PrevalidateCandidates(array $scaryInputs, bool $doExpectCompleteness): GfValid
    {
        foreach ($scaryInputs as $key=>$val) {
            $gfv =  static::PrevalidateKeyVal($key,$val);
            if (! $gfv->isValid()) {
                return $gfv;
            }
        }

        if ($doExpectCompleteness) {
            $gfv = static::ensureNoMissingProperties(array_keys($scaryInputs));
            if (! $gfv->isValid()) {
                return $gfv;
            }
        }

        return GfValid::ConstructValid();
    }

    //    public function getValidity(): GfValid
    //    {
    //        foreach (self::GetManagedPropertyNames() as $managedPropertyName) {
    //            if (! isset($this->$$managedPropertyName)) {
    //                return GfValid::ConstructInvalid("NotSEt($managedPropertyName)");
    //            }
    //        }
    //        return GfValid::ConstructValid();
    //    }
    protected static function Validate_Custom(string $key, $val): GfValid {
        $methodNameThatWouldStaticallyValidate = "Validates_{$key}";
        if (method_exists(get_called_class(),$methodNameThatWouldStaticallyValidate)) {
            /** @var GfValid $gfv */
            $gfv = static::$methodNameThatWouldStaticallyValidate($val);
            $gfv->setOffendingValues([$key]);
            if (! $gfv->isValid()) {
                return $gfv;
            }
        }
        return GfValid::ConstructValid();
    }
    protected static function PrevalidateKeyVal(string $key, $val): GfValid {
        if (! self::IsSpecifiedName_thatOfAManagedProperty($key)) {
            $v = GfValid::ConstructInvalid('extraField');
            $v->setOffendingValues([$key]);
            return $v;
        }

        // type good
        $asr = static::GetManagedPropertyNames2Types();
        $expectedType = $asr[$key];
        $actualType = gettype($val);
        if ($actualType != $expectedType) {
            $v = GfValid::ConstructInvalid("WrongType: Expected $expectedType, Got $actualType");
            $v->setOffendingValues([$key]);
            return $v;
        }

        // functionally good
        $v = static::Validate_Custom($key,$val);
        if (! $v->isValid()) {
            return $v;
        }

        return GfValid::ConstructValid();
    }
}

class Test_006v_Sample_Test extends TestCase
{


    function testType2()
    {
        $o = new GfdPersonv();
        $this->assertTrue(is_array(GfdPersonv::));
    }


}
