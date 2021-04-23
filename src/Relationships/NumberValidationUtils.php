<?php
declare(strict_types=1);
namespace Gfd\Relationships;

final class NumberValidationUtils {
    /* return true if -1,0,1, etc. '0', '1',
  return false on -1.1. 0.1, 1.1, etc
  return true on 1.0 0.0000, etc. // Hey, wth*ck?  PHP converts int 1.0 to 1, instantly.  If you have
      $a = 1.0
      $sql=" WHERE id = $a", then that string will look like (WHERE id = 1).  It's .0 is dropped.
      I would like to catch it but, it long goe before we get to it.
  returns false on '1.0', '1.000', '0.000' etc.
  return false on 0001, +0, -1, '01'
  Motivation: Could this be a database id?
  */
    public static function IsInteger($value): bool { //http://www.php.net/manual/en/function.is-int.php#82857
        //https://stackoverflow.com/a/31070960/93933
        /*
        $int = 999999999999999999;
        $min = 1;
        $max = 2147483647;

        if (filter_var($int, FILTER_VALIDATE_INT, array("options"=>
                array("min_range"=>$min, "max_range"=>$max))) === false) {
            echo("Variable value is not within the legal range");
        } else {
            echo("Variable value is within the legal range");
        }
    */
        //return is_int(filter_var($input, FILTER_VALIDATE_INT));//http://php.net/manual/en/function.ctype-digit.php#118121

        #if ($value ==
        return
            is_numeric($value) &&
            is_int(filter_var($value, FILTER_VALIDATE_INT))
            && (strpos( $value.'', '.' ) === false) // not catch 1.0
            && (strpos( $value, '+' ) === false)
            && ($value !== '-0')
            ;//https://stackoverflow.com/a/8981883/93933

        /*  if (is_array($input)) {
              return false;
          }
          return(ctype_digit(strval($input)));
        */
    }


    public static function IsWhole($input): bool { //http://www.php.net/manual/en/function.is-int.php#82857
        if (static::IsInteger($input.'') && $input >= 0) {
            return true;
        } else {
            return false;
        }
    }

    static function IsDecimal( $val ) : bool //http://stackoverflow.com/a/6772657/93933
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }
}
