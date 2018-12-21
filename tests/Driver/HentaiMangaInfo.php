<?php

namespace YameteTests\Driver;


class HentaiMangaInfo extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentaimanga.info/cozy-cozy-gyaru-sex-hentai-comics/';
        $driver = new \Yamete\Driver\HentaiMangaInfo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(14, count($driver->getDownloadables()));
    }
}
