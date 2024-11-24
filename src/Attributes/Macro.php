<?php

namespace TheFlowByte\MacroAttribute\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Macro
{
    /**
     * Registers a method as a macro for the target class.
     *
     * This attribute is used to bind a specific method to a macro in the given target class.
     * If no macro name is provided, the name of the method to which this attribute is applied
     * will be used as the default macro name.
     *
     * @param  string|array  $targetClass  The class where the macro will be registered.
     * @param  string|null  $macroName  The name of the macro. Defaults to the method name if not specified.
     */
    public function __construct(
        public string|array $targetClass,
        public ?string $macroName = null
    ) {}
}
