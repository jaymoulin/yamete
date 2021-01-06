<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class KissHentaiTvCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://kisshentaitv.com/nobita-shizuka-sex/';
        $driver = new \Yamete\Driver\KissHentaiTvCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
