<?php

namespace YameteTests\Driver;


class VerpornocomicCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://verpornocomic.com/croc-padrinos-magicos-rompiendo-reglas-4/';
        $driver = new \Yamete\Driver\VerpornocomicCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
