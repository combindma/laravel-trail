<?php

namespace Combindma\Trail\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Combindma\Trail\Trail
 */
class Trail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Combindma\Trail\Trail::class;
    }
}
