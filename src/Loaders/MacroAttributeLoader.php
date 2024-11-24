<?php

namespace TheFlowByte\MacroAttribute\Loaders;

use TheFlowByte\MacroAttribute\Attributes\Macro;

class MacroAttributeLoader
{
    /**
     * Binds macros to methods defined in the provided classes.
     *
     * This method scans the specified classes for methods annotated with
     * the #[Macro] attribute. It then registers these methods as macros
     * for their respective target classes.
     *
     * @param  array  $classes  Array of class names to scan for #[Macro] attributes.
     */
    public static function register(array $classes): void
    {
        foreach ($classes as $class) {
            $reflection = new \ReflectionClass($class);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_STATIC) as $method) {
                $attributes = $method->getAttributes(Macro::class);
                $staticMethodName = $method->getName();

                foreach ($attributes as $attribute) {
                    $macro = $attribute->newInstance();
                    $macroName = $macro->macroName ?? $staticMethodName;
                    $targetClasses = is_string($macro->targetClass) ? [$macro->targetClass] : $macro->targetClass;

                    foreach ($targetClasses as $targetClass) {
                        if (method_exists($targetClass, 'macro')) {
                            $targetClass::macro($macroName, function (...$args) use ($class, $staticMethodName) {
                                return $class::$staticMethodName($this, ...$args);
                            });
                        }
                    }
                }
            }
        }
    }
}
