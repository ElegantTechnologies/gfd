<?php
declare(strict_types=1);

namespace TestWorld;

use Gfd\Core\Gfd_ManagedProperties_ImplementationOf_ListingAndStatus_asClassProperties;
use Gfd\Core\Gfd_ManagedProperties_ListingProvider_Interface;
use Gfd\Core\Gfd_Validations_ImplementationViaMethodsStrategy_Stm;
use Gfd\Core\Gfd_ManagedProperties_SetStatusProvider_Interface;
use Gfd\Core\GfValid;
use PHPUnit\Framework\TestCase;
use Gfd\Core\Gfd_SimpleInits_Interface;
use Gfd\Core\Gfd_SimpleInits_Implementation;


class Something4b2 implements Gfd_ManagedProperties_ListingProvider_Interface
{
    public const SLUG_ExtendedCareBaseSlug = 'ExtendedCareSlug';
    public const SLUG_DismissalComment = 'DismissalComment';
    public const SLUG_DismissalType = 'DismissalType';
    private const MANAGED_INPUTS = [
        self::SLUG_ExtendedCareBaseSlug,
        self::SLUG_DismissalComment,
        self::SLUG_DismissalType,
    ];
    public static function GetManagedPropertyNames(): array
    {
        return self::MANAGED_INPUTS;
    }

    public static function IsSpecifiedName_thatOfAManagedProperty(string $nameOfManagedProperty): bool
    {
        return in_arraY($nameOfManagedProperty, self::GetManagedPropertyNames());
    }
}

class Test_004b2_ManagedProerties_notReflected_Test extends TestCase
{
    function test4() {
        $arr = Something4b2::GetManagedPropertyNames();
        $this->assertIsArray($arr);
        $this->assertTrue(in_array(Something4b2::SLUG_DismissalType, $arr));
    }
}
