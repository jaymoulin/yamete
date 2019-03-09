<?php

namespace YameteTests\Driver;


class HentaiSchoolCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentaischool.com/one-shots/zutto-isshoni-together-forever/';
        $driver = new \Yamete\Driver\HentaiSchoolCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
