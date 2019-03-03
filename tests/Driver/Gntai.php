<?php

namespace YameteTests\Driver;


class Gntai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.gntai.xyz/2017/06/choukyourankou.html';
        $driver = new \Yamete\Driver\Gntai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
