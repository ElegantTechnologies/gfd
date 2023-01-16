<?php
declare(strict_types=1);

namespace Gfd\Core;

class GfValid {
    protected bool $isValid;
    protected string $enumReason = '';
    protected string $message = '';

    protected mixed $newValue;
    protected mixed $oldValue;
    protected array $offendingValues; // A little trick to help ensure the receiver knows what to expect.

    public function setOffendingValues($offendingValues): self {
        $this->offendingValues = $offendingValues;
        return $this;
    }
    public function getOffendingValues() : array {
        return $this->offendingValues;
    }

    public static function ConstructValid(): self {
        $me = new static();
        $me->isValid = true;
        return $me;
        #return new static(['isValid'=>true]);
    }
    public function setOldValue($oldValue): self {
        $this->oldValue = $oldValue;
        return $this;
    }
    public function setNewValue($newValue): self {
        $this->newValue = $newValue;
        return $this;
    }

    public static function ConstructInvalid(string $enumReason = 'defaultReason', string $message = ''): self {
        $me = new static();
        $me->isValid = false;
        $me->enumReason = $enumReason;
        $me->message = $message;
        return $me;
        #return new static(['isValid'=>false, 'enumReason'=>$enumReason, 'message'=>$message]);
    }
    public function isValid(): bool {
        return $this->isValid;
    }

    public static function ConstructFromBool(bool $isValid, string $enumReasonIfFalse = '', string $message = ''): self {
        return $isValid ? GfValid::ConstructValid() : GfValid::ConstructInvalid($enumReasonIfFalse, $message);
    }
    public function getReason(): string {
        return $this->enumReason;
    }
    public function getOldValue() {
        return $this->oldValue;
    }
    public function getNewValue() {
        return $this->newValue;
    }

    public function getMessage(): string {
        if (empty($this->message)) {
            if (empty($this->enumReason)) {
                return "Generically invalid";
            } else {
                if ($this->enumReason == 'notAuthenticated') {
                    return "You are not authorized to perform this action. ({$this->enumReason})";
                } elseif ($this->enumReason == 'notNumeric') {
                    return "Expected a numeric value.  ({$this->enumReason})";
                } elseif ($this->enumReason == 'notNonNegative') {
                    return "Expected non-negative value. ({$this->enumReason})";
                } else {
                    return $this->enumReason;
                }
            }
        } else {
            return $this->message;
        }
    }
}