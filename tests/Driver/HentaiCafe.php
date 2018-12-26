<?php

namespace YameteTests\Driver;


class HentaiCafe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentai.cafe/kohsaka-donten-once-bitten-twice-shy/';
        $driver = new \Yamete\Driver\HentaiCafe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
