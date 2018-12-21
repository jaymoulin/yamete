<?php

namespace YameteTests\Driver;


class YaoiMangaOnline extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://yaoimangaonline.com/shingeki-no-kyojin-dj-shitataru-ase-no-itteki-made-omty-makino-jp/';
        $driver = new \Yamete\Driver\YaoiMangaOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
