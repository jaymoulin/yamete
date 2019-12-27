<?php

namespace YameteTests\Driver;


class PornGamesHDCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://porngameshd.com/doujin/tails-cream-rus/index.html';
        $driver = new \Yamete\Driver\PornGamesHDCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
