<?php

namespace YameteTests\Driver;


class GomangaXyz extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://gomanga.xyz/manga/k-on-miottemasu%E2%99%AA-doujinshi';
        $driver = new \Yamete\Driver\GomangaXyz();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(31, count($driver->getDownloadables()));
    }
}
