# Laravel Macro Attribute

[![Latest Version](https://img.shields.io/packagist/v/theflowbyte/laravel-macro-attribute.svg?style=flat-square)](https://packagist.org/packages/theflowbyte/laravel-macro-attribute)

**Laravel Macro Attribute** simplifies binding macros to methods in Laravel using PHP 8 attributes. Define macros with the `#[Macro]` attribute, and they are registered for one or more target classes.

---

## Features

- **Simple Macro Registration**: Bind macros to one or multiple Laravel classes like `Collection`, `Request`, and others.
- **Powered by PHP 8 Attributes**: Clean and modern approach with minimal boilerplate.
- **Custom Macro Names**: Use the default method name or specify custom macro names.
- **Support for Multiple Classes**: A single macro can be registered for multiple target classes.

---

## Installation

Require the package via Composer:

```bash
composer require theflowbyte/laravel-macro-attribute
```

## Usage

### Step 1: Define Macros with the `#[Macro]` Attribute

Create a class with static methods and use the `#[Macro]` attribute to bind them to one or more target classes.

```php
namespace App\Helpers;

use TheFlowByte\MacroAttribute\Attributes\Macro;
use Illuminate\Http\Request;

class CrawlerHelper
{
    #[Macro(targetClass: Request::class)]
    public static function isCrawler(Request $request): bool
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        
        return str_contains($userAgent, 'bot');
    }

    #[Macro(targetClass: Request::class, macroName: 'denyCrawlerAccess')]
    public static function shouldBlockCrawler(Request $request): bool
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        
        $blockedBots = config('bots.blacklist', []);

        foreach ($blockedBots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return true;
            }
        }

        return false;
    }
}
```

### Step 2: Register Classes with the `#[Macro]` Attribute

Register the classes containing the macros using MacroAttributeBinder:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TheFlowByte\MacroAttribute\Loaders\MacroAttributeLoader;
use App\Helpers\CrawlerHelper;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the macros from the specified class
        MacroAttributeLoader::register([
            CrawlerHelper::class,
            // ...
        ]);
    }
}
```

### Step 3: Use the Macros

Once registered, the macros are available on the target classes:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockCrawlers
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isCrawler()) {
            logger()->debug('Request identified as coming from a crawler.');
        }

        if ($request->denyCrawlerAccess()) {
            return response('Access Denied for Crawlers.', 403);
        }

        return $next($request);
    }
}
```

### Advanced Features

**Support for Multiple Target Classes**

You can bind a single macro to multiple target classes by passing an array of class names to the targetClass parameter:

```php

use TheFlowByte\MacroAttribute\Attributes\Macro;
use App\Models\Order;
use App\Models\Invoice;

class FinancialMacros
{
    #[Macro(targetClass: [Order::class, Invoice::class])]
    public static function calculateTotalWithTax(HasSubtotal $model, float $taxRate = 0.2): float
    {
        return $model->subtotal + ($model->subtotal * $taxRate);
    }
}
```

## TODO

### Features to Implement

1. **Code Generator for IDE Autocomplete**
    - Create a standalone repository for a tool that generates PHPDoc annotations for registered macros.
    - Enhance IDE support for Laravel macros, providing autocomplete in PHPStorm, VSCode, etc.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss your ideas.

## License

The MIT License (MIT).
