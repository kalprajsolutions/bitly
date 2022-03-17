<?php

namespace KalprajSolutions\Bitly\Facade;

use Illuminate\Support\Facades\Facade;
use KalprajSolutions\Bitly\Testing\BitlyClientFake;

/**
 * Bitly is a facade for the Bitly client.
 *
 * @see \KalprajSolutions\Bitly\Client\BitlyClient
 *
 * @method string getUrl(string $url)
 */
class Bitly extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'bitly';
    }

    /**
     * Replace the bound instance with a fake.
     *
     * @return \KalprajSolutions\Bitly\Testing\BitlyClientFake
     */
    public static function fake()
    {
        static::swap($fake = new BitlyClientFake);

        return $fake;
    }
}
