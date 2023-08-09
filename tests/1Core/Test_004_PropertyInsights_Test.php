<?php
declare(strict_types=1);

namespace TestWorld;

use Gfd\Core\Gfd_ManagedProperties_ListingProvider_Interface;
use Gfd\Core\Gfd_ManagedProperties_ImplementationOf_ListingAndStatus_asClassProperties;
use Gfd\Core\Gfd_Validations_ImplementationViaMethodsStrategy_Stm;
use Gfd\Core\Gfd_ManagedProperties_SetStatusProvider_Interface;
use Gfd\Core\GfValid;
use PHPUnit\Framework\TestCase;
use Gfd\Core\Gfd_SimpleInits_Interface;
use Gfd\Core\Gfd_SimpleInits_Implementation;


class Something4 implements Gfd_ManagedProperties_SetStatusProvider_Interface, Gfd_ManagedProperties_ListingProvider_Interface
{
    use Gfd_ManagedProperties_ImplementationOf_ListingAndStatus_asClassProperties;
    public int $value;


}

class Something4b implements Gfd_ManagedProperties_SetStatusProvider_Interface, Gfd_ManagedProperties_ListingProvider_Interface
{
    use Gfd_ManagedProperties_ImplementationOf_ListingAndStatus_asClassProperties;

    public int $value;
    public int $value2;
    public int $value3;
}

class Something4c implements Gfd_ManagedProperties_SetStatusProvider_Interface, Gfd_ManagedProperties_ListingProvider_Interface
{
    use Gfd_ManagedProperties_ImplementationOf_ListingAndStatus_asClassProperties;

    public int $value;
    public int $value2 = 2;
    public ?int $value3 = 3;
    public ?int $value4 = null;
}


class Something4d implements Gfd_ManagedProperties_SetStatusProvider_Interface, Gfd_ManagedProperties_ListingProvider_Interface
{
    use Gfd_ManagedProperties_ImplementationOf_ListingAndStatus_asClassProperties;

    public int $value;
    public int $value2 = 2;
    public ?int $value3 = 3;
    public ?int $value4 = null;
    public $untypedAndUnset;
    public $untypedAndDefaultedToOne = 1;
}

class Test_004_PropertyInsights_Test extends TestCase
{
    function test4() {
        $arr = Something4::GetManagedPropertyNames();
        $this->assertIsArray($arr);
        $this->assertTrue(array_search('value',$arr) === 0);
    }
    function test4b() {
        $arr = Something4b::GetManagedPropertyNames();
        $this->assertIsArray($arr);
        $this->assertTrue(array_search('value',$arr) !== false);
        $this->assertTrue(array_search('value2',$arr) !== false);
        $this->assertTrue(array_search('value3',$arr) !== false);
        $this->assertTrue(count($arr) == 3 );
    }

    function test4c() {
        $arr = Something4c::GetManagedPropertyNames();
        $this->assertIsArray($arr);
        $this->assertTrue(array_search('value',$arr) !== false);
        $this->assertTrue(array_search('value2',$arr) !== false);
        $this->assertTrue(array_search('value3',$arr) !== false);
        $this->assertTrue(array_search('value4',$arr) !== false);
        $this->assertTrue(count($arr) == 4 );
    }

    function test4c_whatWasSet_1() {
        $this->test4c();
        $gfd = new Something4c();
        $arr = $gfd->getManagedPropertiesThatAreSet_butExcludeNullValues();
        $this->assertFalse(array_search('value',$arr) !== false);
        $this->assertTrue(array_search('value2',$arr) !== false);
        $this->assertTrue(array_search('value3',$arr) !== false);
        $this->assertFalse(array_search('value4',$arr) !== false);
        $this->assertTrue(count($arr) == 2 );
    }

    function test4d_whatWasSet_1() {
        $gfd = new Something4d();
        $arr = $gfd->getManagedPropertiesThatAreSet_butExcludeNullValues();
        $this->assertFalse(array_search('value',$arr) !== false);
        $this->assertTrue(array_search('value2',$arr) !== false);
        $this->assertTrue(array_search('value3',$arr) !== false);
        $this->assertFalse(array_search('value4',$arr) !== false);

        $this->assertFalse(array_search('untypedAndUnset',$arr) !== false);
        $this->assertTrue(array_search('untypedAndDefaultedToOne',$arr) !== false);

        $this->assertTrue(count($arr) == 3 );


        $gfd->untypedAndUnset = 99;
        $arr = $gfd->getManagedPropertiesThatAreSet_butExcludeNullValues();
        $this->assertTrue(count($arr) == 4 );
    }
    function test4d_whatWasNotSet()
    {
        $gfd = new Something4d();
        $arr = $gfd->getManagedPropertiesThatAreSet_butExcludeNullValues();
        $this->assertTrue(count($arr) == 3);

        $arr = $gfd->getManagedPropertiesThatAreNotYetSet();
        $this->assertTrue(array_search('value',$arr) !== false);
        $this->assertFalse(array_search('untypedAndUnset',$arr) !== false); // Hint: Untyped properties always default to null.
        $this->assertTrue(count($arr) == 1 );

        $arr = $gfd->getManagedPropertiesThatAreSet_butIncludeNullValues();
        $this->assertFalse(array_search('value',$arr) !== false);
        $this->assertTrue(array_search('value2',$arr) !== false);
        $this->assertTrue(array_search('value3',$arr) !== false);
        $this->assertTrue(array_search('value4',$arr) !== false);
        $this->assertTrue(array_search('untypedAndUnset',$arr) !== false);
        $this->assertTrue(array_search('untypedAndDefaultedToOne',$arr) !== false);
        $this->assertTrue(count($arr) == 5 );

    }


}
