<?php

namespace YameteTests\Driver;


class MangaEdenCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangaeden.com/en/en-manga/ase-to-sekken/';
        $driver = new \Yamete\Driver\MangaEdenCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(226, count($driver->getDownloadables()));
    }
}
