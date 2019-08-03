<?php

namespace YameteTests\Driver;


class Gntai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.gntai.xyz/2019/07/quieres-echar-un-vistazo-por-ti-mismo.html';
        $driver = new \Yamete\Driver\Gntai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(17, count($driver->getDownloadables()));
    }
}
