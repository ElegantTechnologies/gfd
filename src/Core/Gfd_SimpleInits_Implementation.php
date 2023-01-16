<?php
#declare(strict_types=1); // can't be here per InitByArrayStrings. '1' can't be assigned to public int $count when strict. You could to introspection, but I don't think it is worth the speed cost.
namespace Gfd\Core;

trait Gfd_SimpleInits_Implementation {
    public static function InitByCorrectArray(array $uncheckedValues) : static {
        $meName =  get_called_class();
        $that = new $meName();
        foreach ($uncheckedValues as $propertyName=>$value) {
            $that->$propertyName = $value;
        }
        return $that;
    }

    public static function InitByArrayStrings(array $uncheckedValues) : static {
        $meName =  get_called_class();
        $that = new $meName();
        foreach ($uncheckedValues as $propertyName=>$value) {
            $methodName = "{$propertyName}_fromString";
            if (method_exists($that, $methodName)) {
                $that->$propertyName = $that::$methodName($value);
            } else {
                $that->$propertyName = $value;
            }
        }
        return $that;
    }
    // no goes here
    //
    #abstract public static function InitFromNothing_withPkString(string $pkYYY_dash_MM_dash_dd): self;
}
