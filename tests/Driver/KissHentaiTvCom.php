<?php

namespace YameteTests\Driver;


class KissHentaiTvCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://kisshentaitv.com/nobita-and-shizuka-xxx/';
        $driver = new \Yamete\Driver\KissHentaiTvCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
