<?php
declare(strict_types=1);
namespace Gfd\Core;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

final class Gfd_ManagedProperties_Listing_meets_classReflections_Stm
{
    public static function GetPublicProperties_forClass($classNameOrObject): array {
        #$i = class_implements($classNameOrObject);
        #$n = Gfd_PropertyInsights_Interface::class;
        #\assert(in_array($n , $i));
        $r = new ReflectionClass($classNameOrObject);
        $asrP = $r->getProperties(ReflectionProperty::IS_PUBLIC);
        $arrP = [];
        foreach ($asrP as $p) {
            $n = $p->getName();
            $arrP[] = $n;
        }
        return $arrP;
    }

    public static function GetExpectTypeOfNamedManagedProperty_forClass(string $nameOfManagedProperty, $classNameOrObject): ?string {

        $rp = new ReflectionProperty($classNameOrObject, $nameOfManagedProperty);
        return $rp->getType()->getName();


    }
    public static function IsNamedManagedProperty_ofNamedClass(string $nameOfManagedProperty, string $className): bool {
        $arrManagedProperties = self::GetPublicProperties_forClass($className);
        return in_array($nameOfManagedProperty, $arrManagedProperties);
    }

    public static function IsNamedManagedProperty_ofObj(string $nameOfManagedProperty, object $obj): bool {
        $arrManagedProperties = self::GetPublicProperties_forClass($obj);
        return in_array($nameOfManagedProperty, $arrManagedProperties);
    }


    public static function GetPublicPropertiesThatHaveHaveNonNullValues_ofNamedClass(object $classObject): array {
        // Gotcha: PHP makes untyped properties automatically nullable AND they default to NULL. This is unlike
        //  typed properties, where even 'public ?int $armsUnset_nullable;' won't default to null. This
        //  makes untyped properties less useful from a GFD perspective.
        //  Importantly, an untyped public property will never be 'required' (cuz it is inherently nullable and defaults to null)

        // FYI: public int $value; <-- if not specifically set, it will NOT be in: $asrDefaultProperties = $reflectionClass->getDefaultProperties();
        //      BUT
        //      public $untypedAndUnset; <-- This will be in $asrDefaultProperties and set to null.  Which, yes, in inconsistent and unexpected.

        $arrRequiredPublic = self::GetPublicProperties_forClass($classObject);
        $arr =[];
        foreach ($arrRequiredPublic as $propertyName) {
            $isset = isset($classObject->$propertyName);
            if ($isset) {
                $arr[] = $propertyName;
            }
        }
        return $arr;
    }

    /**
     * @throws ReflectionException
     */
    public static function GetPublicPropertiesWithDefaults_ofClass($objectOrClassName): array {
        $reflectionClass = new ReflectionClass($objectOrClassName);
        #$asrP = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);
        $asrDefaultProperties = $reflectionClass->getDefaultProperties();//or {@see null} if the property doesn't have a default value
        return $asrDefaultProperties;
    }

    // Gotcha: Unlike 'isset', if a property is set to 'null', or defaults to 'null' (even implicitly like 'public $i;') so $i it won't show as here as unset, but $j (from public int $j) would.

    /**
     * @throws ReflectionException
     */
    public static function GetPublicPropertiesThatAreNotYetSet_forClass($objectOrClassName): array {
        $arrRequiredPublic = self::GetPublicProperties_forClass($objectOrClassName);
        $nonNullProperties = self::GetPublicPropertiesThatHaveHaveNonNullValues_ofNamedClass($objectOrClassName);
        $asrPropertiesDefaults = self::GetPublicPropertiesWithDefaults_ofClass($objectOrClassName);
        $arrMissing = array_diff($arrRequiredPublic, $nonNullProperties, array_keys($asrPropertiesDefaults));
        return $arrMissing;
    }

    // Gotcha: Unlike 'isset', if a property is set to 'null', or defaults to 'null' (even implicitly like `public $i;` ) so $i shows up as set, but 'public int $j;' won't show as set0

    /**
     * @throws ReflectionException
     */
    public static function GetPublicPropertiesThatAreSet_forClass($classObject): array {
        $arrRequiredPublic = self::GetPublicProperties_forClass($classObject);
        $nonNullProperties = self::GetPublicPropertiesThatHaveHaveNonNullValues_ofNamedClass($classObject);
        $asrPropertiesDefaults = self::GetPublicPropertiesWithDefaults_ofClass($classObject);
        $arrUnion = array_unique(array_merge($nonNullProperties, array_keys($asrPropertiesDefaults)));
        $arrUnion = array_intersect($arrUnion, $arrRequiredPublic);
        return $arrUnion;
    }

    /**
     * @throws ReflectionException
     */
    public static function PresentPublicPropertiesAsStoopidKeyValArray(object $classObject): array {
        $arrSetProperties = self::GetPublicPropertiesThatAreSet_forClass($classObject);
        $arr = [];
        foreach ($arrSetProperties as $propertyName) {
            $arr[$propertyName] = $classObject->$propertyName;
        }
        return $arr;
    }
}