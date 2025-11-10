
# Laravel Toon

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Author](https://img.shields.io/badge/Author-Tech%20with%20muk-red.svg)](https://github.com/lemukarram)

A lightweight, token-efficient data format specifically designed for optimizing AI prompts in Laravel applications. Developed by **[Mukarram Hussain](https://github.com/lemukarram)** of **Tech with muk**.

When sending data to LLMs (like ChatGPT, Claude, or Gemini), standard JSON uses too many tokens due to repeated keys and braces. This package converts your PHP arrays into a highly compact, human-readable "Toon" notation, saving you money and context window space.

## Features

- 🚀 **`jsonToToon`**: Convert PHP Arrays or JSON to compact notation.
- 🔢 **`countTokens`**: Estimate the token usage of your strings.
- 📉 **Smart Compression**: Automatically converts lists of objects (like Eloquent collections) into a clean, CSV-style table format to save maximum tokens.

## Installation

You can install the package via composer:

```bash
composer require lemukarram/toon
````

The package will automatically register its service provider and facade.

## Usage

### 1\. Compressing Data for AI Prompts

Use the `Toon` facade to convert your data before sending it to an AI API.

```php
use LeMukarram\Toon\Facades\Toon;

$data = [
    'user' => 'Mukarram',
    'skills' => ['Laravel', 'PHP', 'AI', 'CodeIgniter', 'Shopify'],
    'projects' => [
        ['name' => 'AI Influencer', 'status' => 'Active'],
        ['name' => 'Laravel Package', 'status' => 'Done'],
    ]
];

$promptContext = Toon::jsonToToon($data);

/*
 * The output ($promptContext) will be:
 *
 * user: Mukarram
 * skills:
 * 0: Laravel
 * 1: PHP
 * 2: AI
 * 3: CodeIgniter
 * 4: Shopify
 * projects:
 * @[2](name,status):
 * "AI Influencer",Active
 * "Laravel Package",Done
 */
```

### 2\. Estimating Token Usage

Check how many tokens you are saving.

```php
$jsonTokens = Toon::countTokens(json_encode($data));
$toonTokens = Toon::countTokens($promptContext);

echo "JSON Tokens: $jsonTokens"; // e.g., ~70
echo "Toon Tokens: $toonTokens"; // e.g., ~45
```

## Credits

  - **[Mukarram Hussain](https://www.google.com/url?sa=E&source=gmail&q=https://github.com/lemukarram)** (Tech with muk)

## License

The MIT License (MIT). Please see [License File](https://www.google.com/search?q=LICENSE) for more information.

```
```
