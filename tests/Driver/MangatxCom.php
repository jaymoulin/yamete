<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangatxCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangatx.com/manga/infinite-apostles-and-twelve-war-girls/';
        $driver = new \Yamete\Driver\MangatxCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(436, count($driver->getDownloadables()));
    }
}
