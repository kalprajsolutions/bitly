<?php

namespace KalprajSolutions\Bitly\Test\Testing;

use PHPUnit\Framework\TestCase;
use KalprajSolutions\Bitly\Testing\BitlyClientFake;

class BitlyClientFakeTest extends TestCase
{
    /** @var \KalprajSolutions\Bitly\Testing\BitlyClientFake */
    private $bitlyClient;

    protected function setUp() : void
    {
        $this->bitlyClient = new BitlyClientFake();
    }

    public function testGetUrl()
    {
        $shortUrlFoo = $this->bitlyClient->getUrl('https://www.test.com/foo');

        $this->assertTrue(strlen($shortUrlFoo) < 22);
        $this->assertStringContainsString('://bit.ly', $shortUrlFoo);

        $shortUrlBar = $this->bitlyClient->getUrl('https://www.test.com/bar');
        $this->assertNotSame($shortUrlFoo, $shortUrlBar);
    }
}
