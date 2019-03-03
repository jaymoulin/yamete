<?php

namespace YameteTests\Driver;


class DoujinHentaiNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://doujinhentai.net/manga-hentai/navidad-en-el-ascilo/1';
        $driver = new \Yamete\Driver\DoujinHentaiNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
