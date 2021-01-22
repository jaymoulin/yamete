<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaBobCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangabob.com/manga/greatest-sword-immortal/';
        $driver = new \Yamete\Driver\MangaBobCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(386, count($driver->getDownloadables()));
    }
}
