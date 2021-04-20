<?php
declare(strict_types=1);

namespace TestWorld;

use Gfd\Core\Gfd_Validations_Implementation;
use Gfd\Core\Gfd_PropertyInsights_Interface;
use Gfd\Core\GfValid;
use PHPUnit\Framework\TestCase;
use Gfd\Core\Gfd_SimpleInits_Interface;
use Gfd\Core\Gfd_SimpleInits_Implementation;

class GfdPerson
{
    use Gfd_SimpleInits_Implementation;
    use Gfd_Validations_Implementation;


    private $x;        // this is not public - we totally ignore this.

    // public: Type test
    public ?int $armsUnset_nullable;
    public int $feetUnset;
    public ?int $legsSetTo2_nullable = 2;
    public ?int $legsSetToNull_nullable = null;


    // public, untyped
    // Gotcha: PHP makes untyped properties automatically nullable AND they default to NULL. This is unlike
    //  typed properties, where even 'public ?int $armsUnset_nullable;' won't default to null. This
    //  makes untyped properties less useful from a GFD perspective.
    //  Importantly, an untyped public property will never be 'required' (cuz it is inherently nullable and defaults to null)

    public $requiredButNotTyped;   // must be set, but we won't type enforce (php handles this if we access before set)
    public $notRequiredToBeSpecificallySetCuzDefaultsToNull = null;  // optionally set, but we won't type enforce  (php handles this)
    public $notRequiredToBeSpecificallySetCuzDefaultsToZeroNotTyped = 0;  // optionally set (it defaults to zero), but we won't type enforce  (php handles this)

    // public, typed
    public string $name;    // optionally set, but we won't type enforce.   (php handles this)
    public ?int $age; // Must always be specified before using, but can be null (implies we don't know it).
    public int $numEyes = 2;    /* We might, or might not, know the age, and we'll assume that we do know it, unless otherwise specified
                                 * We'll use a custom validator below to keep it between 0 and 2 */
    public bool $likesIceCream = true;

    // protected (
    protected $j; // Not type enforced, but required;
    protected int $stuff;

    public static function numEyes_Validates($wellTypedButOtherwiseUntrustedValue): GfValid
    {
        return GfValid::ConstructFromBool(($wellTypedButOtherwiseUntrustedValue >= 0 && $wellTypedButOtherwiseUntrustedValue <= 2));

   }
}

class GfdAdult extends GfdPerson
{
    public static function age_Validates($wellTypedButOtherwiseUntrustedValue): GfValid
    {
        return GfValid::ConstructFromBool(($wellTypedButOtherwiseUntrustedValue >= 21), 'WrongAge');
    }
}


class Test_006_Sample_Test extends TestCase
{


    function testType2()
    {
        $jj = new GfdAdult();
        $jj->age = 49; // ok - straight php
        //$jj->age = '49'; php type error
        $jj->name = 'JJ'; // ok - straight php

        $jj = GfdAdult::InitByCorrectArray(['name' => 'JJ', 'age' => 49]); //ok
        $maddie = GfdAdult::InitByCorrectArray(['name' => 'JJ', 'age' => 13]);  //ok, cuz init doesn't error check

        $gfv = GfdAdult::PrevalidateCandidates(['name' => 'Maddie', 'age' => 13], false);// will get bad result per age
        $this->assertFalse($gfv->isValid());
        $jj = GfdAdult::InitByCorrectArray(['name' => 'JJ', 'age' => 19]);
        $gfv = $jj->getValidity();
        $this->assertFalse($gfv->isValid());
        $this->assertTrue($gfv->getReason() == 'WrongAge', 'must be 21');
        $jj->armsUnset_nullable = 2;
        $jj->feetUnset = 2;
        $gfv = $jj->getValidity();
        $this->assertFalse($gfv->isValid());
        $this->assertTrue($gfv->getReason() == 'WrongAge');
        $jj->age = 39; // now fix the age
        $gfv = $jj->getValidity();
        $this->assertTrue($gfv->isValid());



        $gfv = GfdAdult::PrevalidateCandidates(['name' => 'JJ', 'age' => 49], false);// will get bad result per age
        $jj->armsUnset_nullable = 2;
        $jj->feetUnset = 2;
        $this->assertTrue($gfv->isValid());


//        $jj = CfdAdult::InitByCorrectArray(['name'=>'Maddie', 'age'=>13])->ValidateSelf(); // throws exception
    }


}
