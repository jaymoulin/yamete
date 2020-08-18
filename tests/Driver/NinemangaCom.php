<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class NinemangaCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://en.ninemanga.com/manga/HAIKYU%21%21+DJ+-+NEKO+WA+GAKUSHUU+SURU.html';
        $driver = new \Yamete\Driver\NinemangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
