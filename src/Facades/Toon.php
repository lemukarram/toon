<?php

namespace LeMukarram\Toon\Facades; // <-- UPDATED NAMESPACE

use Illuminate\Support\Facades\Facade;

/**
 * @method static string jsonToToon(array|string $data)
 * @method static array toonToJson(string $toon)
 * @method static int countTokens(string $text)
 *
 * @see \LeMukarram\Toon\ToonConverter
 */
class Toon extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'toon';
    }
}