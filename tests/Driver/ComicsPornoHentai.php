<?php

namespace YameteTests\Driver;


class ComicsPornoHentai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://comicspornohentai.com/la-ruptura-del-sol-capitulo-1-la-mascota-del-rey-parodia-xxx-my-little-pony-friendship-is-magic-furry-espanol/';
        $driver = new \Yamete\Driver\ComicsPornoHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
