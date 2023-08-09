<?php
declare(strict_types=1);

namespace TestWorld;

use Gfd\Core\Gfd_Validations_ImplementationViaMethodsStrategy_Stm;
use Gfd\Core\Gfd_ManagedProperties_SetStatusProvider_Interface;
use Gfd\Core\Gfd_Validatable_Interface;
use Gfd\Core\GfValid;
use PHPUnit\Framework\TestCase;
use Gfd\Core\Gfd_SimpleInits_Interface;
use Gfd\Core\Gfd_SimpleInits_Implementation;


class SomethingLonger
{
    use Gfd_SimpleInits_Implementation;
    use Gfd_Validations_ImplementationViaMethodsStrategy_Stm;

    public int $value;
    public int $value2;

    static public function value_validates(int $untrustedValue): GfValid
    {
        return ($untrustedValue == 88) ? GfValid::ConstructValid() : GfValid::ConstructInvalid('Not88');
    }
}


class Test_010_LongerValidation_Test extends TestCase
{


    function testIncompleteInputs()
    {
        $asr = ['value' => 88];
        $gfv = SomethingLonger::PrevalidateCandidates($asr, false);
        $this->assertTrue($gfv->isValid());
        $gfv = SomethingLonger::PrevalidateCandidates($asr, true);
        $this->assertFalse($gfv->isValid(),'Good input, but we now require everything.');
        $gfv = SomethingLonger::PrevalidateCandidates(['value' => 88, 'value2'=>1], true);
        $this->assertTrue($gfv->isValid(),'Good input, but we now require everything.');

        $gfd = SomethingLonger::InitByCorrectArray($asr,);
        $this->assertTrue($gfd->value == 88);
        $gfv = $gfd->getValidity();
        $this->assertFalse($gfv->isValid(), 'value 2 is not set');
    }

    function testValidAndEnough()
    {
        $asr = ['value' => 88, 'value2' => 99];
        $this->assertTrue(SomethingLonger::PrevalidateCandidates($asr, true)->isValid());
        #$gfd = SomethingLonger::InitByCorrectArray($asr);
        #$gfv = $gfd->getValidity();
        #$this->assertTrue($gfv->isValid());
    }

}
