<?php
declare(strict_types=1);
namespace Gfd\Core;

/*
 * Do lightly-rich typing and validation
 * Spec:
 *  All public properties, and only public properties, are managed.
 *  At creation, all managed properties must be set, unless there is a specified default.
 *
 *  If a property type(s) is specified, then the type is enforced when set
 *  Type mismatches, and missing properties, through exceptions.
 *  Value validation is checked via public function propertyName_Validates($blah) : DtoValid
 *
 * class GfdPerson implements GfdBase {
 *  uses GfdBase_Implemtation;
 *  uses GfdBase_ValidationImplementation;
 *   Known Limitation (php limitation). If NULL is an option and you don't specify a default, then it defaults to null.
 *   private $i;        // this is not public - we totally ignore this.
 *   protected $j;   // this is not public - we totally ignore this.
 *   public $requiredButNotTyped; // must be set, but we won't type enforce (php handles this)
 *   public $notRequiredAndNotTyped = 0; // optionally set, but we won't type enforce  (php handles this)
 *   public $payloadIfNotNull = null;    // optionally set, but we won't type enforce.   (php handles this)
 *   public string $name; // Must always be specified
 *
 *   public ?int $age = null; // We might, or might not, know the age, and we'll assume that we don't know it
 *   public $numEyes = 2; // We'll assume two eyes, unless otherwise specified.  Never null.
 *   public bool $likesIceCream = true;
 *   public $payload = null; // Not type enforced, but required;
 *   protected $stuff; // not managed at all, but will show up in the list of properties
 *   private $_canNotSeeMe;
 *   public static $x; // tis ok.  treated like public $x. Not sure use case, but wanted PHP consistency.
 *
 *   public static function numEyes_Validates($wellTypedButOtherwiseUntrustedValue) : DtoValid {
 *      $isLegitRange = $wellTypedButOtherwiseUntrustedValue => 0 && $wellTypedButOtherwiseUntrustedValue <= 2;
 *      return new DtoValid(['isValid'=>$isLegitRange]);
 *   }
 *  }
 *
 * class CfdAdult extends CfdPerson {
 *  public static function age_Validates($wellTypedButOtherwiseUntrustedValue) : DtoValid{
 *    $isLegitRange = $wellTypedButOtherwiseUntrustedValue >= 18;
 *    return new DtoValid(['isValid'=>$isLegitRange]);
 *  }
 * }
 * $jj = CfdAdult::InitByCorrectArray(['name'=>'JJ', 'age'=>49])->ValidateSelf(); //ok
 * $jj = CfdAdult::InitByCorrectArray(['name'=>'Maddie', 'age'=>13])->ValidateSelf(); // throws exception
 */

//abstract class GfdBase implements GfdBase_InterfaceaseInterface{
//
//}
