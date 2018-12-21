<?php

namespace YameteTests\Driver;


class Freeadultcomix extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://freeadultcomix.com/redhead-photoshoot-harmonist11/';
        $driver = new \Yamete\Driver\Freeadultcomix();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
