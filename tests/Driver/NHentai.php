<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class NHentai extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://nhentai.com/en/comic/toadstool-factory-mimic-aigan-youdo-02-chinese';
        $driver = new \Yamete\Driver\NHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(40, count($driver->getDownloadables()));
    }
}
