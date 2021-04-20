<?php
declare(strict_types=1);
namespace Gfd\Db;

final class GfdbStm {
    public static function IsArraySubsetOfArray(array $ArrSmall, array $ArrBig): bool{
        $ArrSmall = array_unique($ArrSmall);
        $ArrBig = array_unique($ArrBig);
        foreach ($ArrSmall as $Val) {
            if (!in_array($Val,$ArrBig)) return False;
        }
        return True;
    }
}