<?php

namespace Combindma\Trail\Facades;

use Combindma\Trail\DataTransferObjects\TrailDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Combindma\Trail\Trail
 *
 * @method static void init(Request $request)
 * @method static void setUtmCookies(Request $request)
 * @method static void setReferrerCookies(Request $request)
 * @method static void setUserCookie(Request $request)
 * @method static void enable()
 * @method static void disable()
 * @method static void setTrailCookie(string $name, mixed $value)
 * @method static array|string|null getTrailCookie(Request $request, string $name)
 * @method static void identify(string $userId, ?string $email = null, ?string $name = null)
 * @method static TrailDto data(?Request $request = null)
 */
class Trail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Combindma\Trail\Trail::class;
    }
}
