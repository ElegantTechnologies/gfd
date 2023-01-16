<?php
declare(strict_types=1);
namespace Gfd\Core;

interface Gfd_Validations_Interface
{
    public static function PrevalidateCandidates(array $scaryInputs, bool $doExpectCompleteness): GfValid;
    public function assertValidated (): static;
    public function getValidity (): GfValid;

}