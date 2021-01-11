<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Manga4LifeCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manga4life.com/manga/After-School-Bitchcraft';
        $driver = new \Yamete\Driver\Manga4LifeCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(186, count($driver->getDownloadables()));
    }
}
