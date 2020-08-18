<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Yamete\Driver\ToonPornComCom;

class ToonPornPicsCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.toonpornpics.com/galleries/mix-of-famous-cartoon-anime-hentai-girls';
        $driver = new ToonPornComCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(3, count($driver->getDownloadables()));
    }
}
