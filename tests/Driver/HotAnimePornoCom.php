<?php

namespace YameteTests\Driver;


class HotAnimePornoCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hotanimeporno.com/gallery/manga-hentai-free-pregnant-4/index.html';
        $driver = new \Yamete\Driver\HotAnimePornoCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(5, count($driver->getDownloadables()));
    }
}
