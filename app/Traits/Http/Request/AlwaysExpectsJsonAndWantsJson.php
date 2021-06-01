<?php
namespace App\Traits\Http\Request;

trait AlwaysExpectsJsonAndWantsJson {
    public function expectsJson(): bool
    {
        return true;
    }

    public function wantsJson(): bool
    {
        return true;
    }

}
