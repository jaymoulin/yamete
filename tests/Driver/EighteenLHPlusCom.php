<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class EighteenLHPlusCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://18lhplus.com/manga-silver-street-romantic.html';
        $driver = new \Yamete\Driver\EighteenLHPlusCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(48, count($driver->getDownloadables()));
    }
}
