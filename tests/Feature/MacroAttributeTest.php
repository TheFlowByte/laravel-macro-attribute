<?php

namespace TheFlowByte\MacroAttribute\Tests\Feature;

use TheFlowByte\MacroAttribute\Loaders\MacroAttributeLoader;
use PHPUnit\Framework\TestCase;
use TheFlowByte\MacroAttribute\Tests\Examples\ExampleBinderClassMacros;

class MacroAttributeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        MacroAttributeLoader::register([
            ExampleBinderClassMacros::class,
        ]);
    }

    public function test_macro_upper()
    {
        $collection = collect(['a', 'b', 'c']);
        $result = $collection->toUpper();

        $this->assertEquals(['A', 'B', 'C'], $result->toArray());
    }

    public function test_macro_prefix()
    {
        $collection = collect(['a', 'b', 'c']);
        $result = $collection->addPrefix('X');

        $this->assertEquals(['Xa', 'Xb', 'Xc'], $result->toArray());
    }

    public function test_macro_not_registered_without_attribute()
    {
        $this->expectException(\BadMethodCallException::class);

        $collection = collect(['a', 'b', 'c']);
        $collection->nonExistentMacro();
    }
}
