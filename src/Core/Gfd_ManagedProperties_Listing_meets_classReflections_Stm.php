<?php
declare(strict_types=1);
namespace Gfd\Core;

use ReflectionClass;
use ReflectionProperty;

final class Gfd_PropertyInsights_Stm
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

    public static function GetExpectTypeOfManagedProperty_forClass(string $nameOfManagedProperty, $classNameOrObject): ?string {
        $rp = new ReflectionProperty($classNameOrObject, $nameOfManagedProperty);
        return $rp->getType()->getName();


    }
    public static function IsManagedProperty_ofClassName(string $nameOfManagedProperty, string $className): bool {
        $arrManagedProperties = static::GetPublicProperties_forClass($className);
        return in_array($nameOfManagedProperty, $arrManagedProperties);
    }

    public static function IsManagedProperty_ofObj(string $nameOfManagedProperty, object $obj): bool {
        $arrManagedProperties = static::GetPublicProperties_forClass($obj);
        return in_array($nameOfManagedProperty, $arrManagedProperties);
    }


    public static function GetPublicPropertiesThatHaveHaveNonNullValues_forClass(object $classObject): array {
        // Gotcha: PHP makes untyped properties automatically nullable AND they default to NULL. This is unlike
        //  typed properties, where even 'public ?int $armsUnset_nullable;' won't default to null. This
        //  makes untyped properties less useful from a GFD perspective.
        //  Importantly, an untyped public property will never be 'required' (cuz it is inherently nullable and defaults to null)

        // FYI: public int $value; <-- if not specifically set, it will NOT be in: $asrDefaultProperties = $reflectionClass->getDefaultProperties();
        //      BUT
        //      public $untypedAndUnset; <-- This will be in $asrDefaultProperties and set to null.  Which, yes, in inconsistent and unexpected.

        $arrRequiredPublic = static::GetPublicProperties_forClass($classObject);
        $arr =[];
        foreach ($arrRequiredPublic as $propertyName) {
            $isset = isset($classObject->$propertyName);
            if ($isset) {
                $arr[] = $propertyName;
            }
        }
        return $arr;
    }
    public static function GetPublicPropertiesWithDefaults(object $classObject): array {
        $reflectionClass = new ReflectionClass($classObject);
        #$asrP = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);
        $asrDefaultProperties = $reflectionClass->getDefaultProperties();//or {@see null} if the property doesn't have a default value
        return $asrDefaultProperties;
    }

    // Gotcha: Unlike 'isset', if a property is set to 'null', or defaults to 'null' (even implicitly like 'public $i;') so $i it won't show as here as unset, but $j (from public int $j) would.
    public static function GetPublicPropertiesThatAreNotYetSet_forClass($classObject): array {
        $arrRequiredPublic = static::GetPublicProperties_forClass($classObject);
        $nonNullProperties = static::GetPublicPropertiesThatHaveHaveNonNullValues_forClass($classObject);
        $asrPropertiesDefaults = static::GetPublicPropertiesWithDefaults($classObject);
        $arrMissing = array_diff($arrRequiredPublic, $nonNullProperties, array_keys($asrPropertiesDefaults));
        return $arrMissing;
    }

    // Gotcha: Unlike 'isset', if a property is set to 'null', or defaults to 'null' (even implicitly like `public $i;` ) so $i shows up as set, but 'public int $j;' won't show as set0
    public static function GetPublicPropertiesThatAreSet_forClass($classObject): array {
        $arrRequiredPublic = static::GetPublicProperties_forClass($classObject);
        $nonNullProperties = static::GetPublicPropertiesThatHaveHaveNonNullValues_forClass($classObject);
        $asrPropertiesDefaults = static::GetPublicPropertiesWithDefaults($classObject);
        $arrUnion = array_unique(array_merge($nonNullProperties, array_keys($asrPropertiesDefaults)));
        $arrUnion = array_intersect($arrUnion, $arrRequiredPublic);
        return $arrUnion;
    }

    public static function PresentPublicPropertiesAsStoopidArray(object $classObject): array {
        $arrSetProperties = static::GetPublicPropertiesThatAreSet_forClass($classObject);
        $arr = [];
        foreach ($arrSetProperties as $propertyName) {
            $arr[$propertyName] = $classObject->$propertyName;
        }
        return $arr;
    }
}