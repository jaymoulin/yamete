<?php

namespace YameteTests\Driver;


class MangaReaderMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://mangareader.me/manga/strike-witches-dj-prankster-angel';
        $driver = new \Yamete\Driver\MangaReaderMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
