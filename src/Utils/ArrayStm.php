<?php
namespace Gfd\Utils;

final class ArrayStm {
    //	 * determine if the values of first array make up a subset of the second.  Is each member of the first array in the second array.
    //	 * This ignores the keys
    /*
    public static function IsSubsetOf(array $ArrSmall, array $ArrBig): bool{
        $ArrSmall = array_unique($ArrSmall);
        $ArrBig = array_unique($ArrBig);
        foreach ($ArrSmall as $Val) {
            if (!in_array($Val,$ArrBig)) return False;
        }
        return True;
    }

        // recursively reduces deep arrays to single-dimensional arrays
        // $preserve_keys: (0=>never, 1=>strings, 2=>always)
        // from http://us2.php.net/manual/en/function.array-values.php#77671
        public static function ArrayFlatten(array $array, $preserve_keys = 0, &$newArray = [] ): array {
            if (!is_array($array)) {
                print "Array=>$array";
                EtError::AssertTrue(0, 'error', __FILE__, __LINE__, "not an array.  It is of type:  ".gettype($array));
            }


            foreach ($array as $key => $child) {
                if (is_array($child)) {
                    $newArray = ArrayStm::ArrayFlatten($child, $preserve_keys, $newArray); // used to have a & after the '='
                } elseif ($preserve_keys + is_string($key) > 1) {
                    $newArray[$key] = $child;
                } else {
                    $newArray[] = $child;
                }
            }
            return $newArray;
        }


        // like in_array, but looks in sub-arrays
         // @return bool true if the value was found, false otherwise
         // see: http://us3.php.net/manual/en/function.in-array.php#82328
        public static function InArrayDeep($value, array $array, $case_insensitive = false): bool {
            foreach($array as $item) {
                if(is_array($item))
                    $ret = ArrayStm::InArrayDeep($value, $item, $case_insensitive);
                else
                    $ret = ($case_insensitive) ? strtolower($item)==strtolower($value) : $item==$value;
                if($ret)
                    return $ret;
            }
            return false;
        }


        // @returns fingerprint of an array.  usefule for comparing quickly - just the values, not the kes
        public static function Fingerprint($Asr): string {
            return md5(implode(',',ArrayStm::ArrayFlatten($Asr)));
        }

        // @returns boolean true if this is an associative array, flase, if a numerically indexed array
        // Motivation: I wanted to talk to the iphone via a xml plist.  it needs to be an <dict> if associate, and <array> if a plan vector
        // From: http://us2.php.net/manual/en/function.is-array.php#84488
        public static function IsAsr($AsrMaybeOrArr): bool {
            foreach (array_keys($AsrMaybeOrArr) as $k => $v) {
              if ($k !== $v)
                return true;
            }
            return false;
        }


        // Motivation: I started using php 5's accible interface, which is practically an array - when a param needs to be either an array or accessible, call this instead of is_array
        public static function is_arrayish($ArrOrMaybeAccessible): bool {
            return (bool)($ArrOrMaybeAccessible instanceof ArrayAccess or is_array($ArrOrMaybeAccessible));
        }


        public static function RecursiveDiff($aArray1, $aArray2): array { //from http://stackoverflow.com/a/3877494/93933
          $aReturn = array();

          foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
              if (is_array($mValue)) {
                $aRecursiveDiff = ArrayStm::RecursiveDiff($mValue, $aArray2[$mKey]);
                if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
              } else {
                if ($mValue != $aArray2[$mKey]) {
                  $aReturn[$mKey] = $mValue;
                }
              }
            } else {
              $aReturn[$mKey] = $mValue;
            }
          }
          return $aReturn;
        }

        // Compares the values of two arrays (but not recursively/deeply) and ignores keys and order of the values
        //So you can read as, "Do these contain the same payload of stuff"

        public static function SameValues(array $a, array $b): bool {  // from: http://stackoverflow.com/a/6922213/93933

            return array_diff($a, $b) === array_diff($b, $a);
        }
        */
}
