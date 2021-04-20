<?php
declare(strict_types=1);
namespace Gfd\Core;
interface GfValid_Provider_interface {
    public function getValidity(): GfValid;
}