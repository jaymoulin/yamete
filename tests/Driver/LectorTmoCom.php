<?php

namespace YameteTests\Driver;


class LectorTmoCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://lectortmo.com/library/doujinshi/40044/una-semana-para-nosotros-yuri-on-ice-dj';
        $driver = new \Yamete\Driver\LectorTmoCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(42, count($driver->getDownloadables()));
    }
}
