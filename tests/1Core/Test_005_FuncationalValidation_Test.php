<?php
declare(strict_types=1);

namespace TestWorld;

use Gfd\Core\Gfd_ManagedProperties_Listing_meets_classReflections_Stm;
use Gfd\Core\Gfd_Validations_ImplementationViaMethodsStrategy_Stm;
use Gfd\Core\GfValid;
use PHPUnit\Framework\TestCase;
use Gfd\Core\Gfd_SimpleInits_Implementation;
class Something0 {
    public $a;
}

class Something1
{
    use Gfd_SimpleInits_Implementation;
    use Gfd_Validations_ImplementationViaMethodsStrategy_Stm;

    public int $value;

    static public function value_validates(int $untrustedValue): GfValid
    {
        return ($untrustedValue == 88) ? GfValid::ConstructValid() : GfValid::ConstructInvalid('Not88');
    }
}


class Test_005_FuncationalValidation_Test extends TestCase
{
    function testPropertyThere() {
        $this->assertTrue(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofNamedClass('a',Something0::class));
        $this->assertFalse(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofNamedClass('z',Something0::class));

        $o = new Something0();
        $this->assertTrue(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofObj('a',$o));
        $this->assertFalse(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofObj('z',$o));
    }

    function testProposedKVs() {
        $this->assertTrue(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofNamedClass('a',Something0::class));
        $this->assertFalse(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofNamedClass('z',Something0::class));

        $o = new Something0();
        $this->assertTrue(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofObj('a',$o));
        $this->assertFalse(Gfd_ManagedProperties_Listing_meets_classReflections_Stm::IsNamedManagedProperty_ofObj('z',$o));
    }



    function testType2()
    {
        $gfd = Something1::InitByCorrectArray([]);
        $this->assertTrue(true, "Should  see this");

        $gfv = Something1::PrevalidateCandidates([], false);
        $this->assertTrue($gfv->isValid(), "Should  see this: ");

        $gfv = Something1::PrevalidateCandidates(['bob' => 1], false);
        $this->assertFalse($gfv->isValid(), "Bob is extra ");

        $gfv = Something1::PrevalidateCandidates(['value' => 89], false);
        $this->assertFalse($gfv->isValid(), "Nope: " . $gfv->getMessage());

        $gfv = Something1::PrevalidateCandidates(['value' => 88], false);
        $this->assertTrue($gfv->isValid(), "Nope: " . $gfv->getMessage());
    }

    function testMissingFields()
    {
        $gfd = Something1::InitByCorrectArray([]);
        $this->assertTrue(true, "Should  see this");

        $gfv = Something1::PrevalidateCandidates([], true);
        $this->assertFalse($gfv->isValid(), "value is missing ");

        $gfv = Something1::PrevalidateCandidates(['value' => 1], true);
        $this->assertFalse($gfv->isValid(), "ok, got our value, but it isnt 88, as is holy ");
        $gfv = Something1::PrevalidateCandidates(['value' => 88], true);
        $this->assertTrue($gfv->isValid(), "ok, ");

        $gfv = Something1::PrevalidateCandidates(['value' => 1, 'bob'=>1], true);
        $this->assertFalse($gfv->isValid(), "Got value, but bob is still extra");

        $gfv = Something1::PrevalidateCandidates(['bob' => 89], false);
        $this->assertFalse($gfv->isValid(), 'got a value, but still missing a field');
    }


    function testChainer()
    {
        $asr = ['value' => 88];
        $gfv = Something1::PrevalidateCandidates($asr, true);
        $this->assertTrue($gfv->isValid(), "Nope: " . $gfv->getMessage());
        $gfd = Something1::InitByCorrectArray($asr);
        $this->assertTrue($gfd->value == 88);
    }
    function testTypes()
    {
        $asr = ['value' => '88'];
        $gfv = Something1::PrevalidateCandidates($asr, true);
        $this->assertFalse($gfv->isValid(), "Nope: " . $gfv->getMessage());
        $this->assertTrue($gfv->getReason() == 'badType', $gfv->getReason());

        $asr['value'] = 88;
        $gfd = Something1::InitByCorrectArray($asr);
        $this->assertTrue($gfd->value == 88);
    }

    function testChainer_inspectOffendingValue()
    {
        $asr = ['value' => 87];
        $gfv = Something1::PrevalidateCandidates($asr, true);
        $this->assertFalse($gfv->isValid(), "Nope: " . $gfv->getMessage());
        $this->assertTrue($gfv->getOffendingValues()[0] == 'value' ,);
    }


}
