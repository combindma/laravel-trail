<?php

namespace Combindma\Trail\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Combindma\Trail\Trail
 *
 * @method static void init(Request $request)
 * @method static void setUtmCookies(Request $request)
 * @method static void setReferrerCookies(Request $request)
 */
class Trail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Combindma\Trail\Trail::class;
    }
}
