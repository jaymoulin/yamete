<?php

namespace YameteTests\Driver;


class HentaiGratisBiz extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaigratis.biz/hentai-melody/';
        $driver = new \Yamete\Driver\HentaiGratisBiz();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
