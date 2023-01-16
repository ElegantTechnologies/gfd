<?php
declare(strict_types=1);
namespace Gfd\Core;

interface Gfd_SimpleInits_Interface {
    public static function InitByCorrectArray(array $uncheckedValues) : static;
    public static function InitByArrayStrings(array $uncheckedValues) : static;

}