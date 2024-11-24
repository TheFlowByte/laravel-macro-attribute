<?php

namespace TheFlowByte\MacroAttribute\Tests\Examples;

use Illuminate\Support\Collection;
use TheFlowByte\MacroAttribute\Attributes\Macro;

class ExampleBinderClassMacros
{
    #[Macro(targetClass: Collection::class)]
    public static function toUpper(Collection $items)
    {
        return $items->map(fn($item) => strtoupper($item));
    }

    #[Macro(targetClass: Collection::class)]
    public static function addPrefix(Collection $collection, string $prefix)
    {
        return $collection->map(fn($item) => $prefix . $item);
    }

    public static function nonExistentMacro(): void
    {

    }
}
