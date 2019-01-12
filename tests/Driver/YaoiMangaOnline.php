<?php

namespace YameteTests\Driver;


class YaoiMangaOnline extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://yaoimangaonline.com/shingeki-no-kyoujin-dj-shounen-kakkyuu-by-hpk-kurosuke-aa-eng/';
        $driver = new \Yamete\Driver\YaoiMangaOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(44, count($driver->getDownloadables()));
    }
}
