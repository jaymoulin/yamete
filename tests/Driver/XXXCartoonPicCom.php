<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class XXXCartoonPicCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.xxxcartoonpic.com/galleries/busted-18166';
        $driver = new \Yamete\Driver\XXXCartoonPicCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
