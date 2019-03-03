<?php

namespace YameteTests\Driver;


class SexComicOrg extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://sexcomic.org/los-padrinos-magicos-xxx-cosmo-y-wanda-follando/';
        $driver = new \Yamete\Driver\SexComicOrg();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
