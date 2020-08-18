<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ZizkiCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://zizki.com/jiro-chiba/were-slut-8-venus';
        $driver = new \Yamete\Driver\ZizkiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23, count($driver->getDownloadables()));
    }
}
