<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaEdenCom extends TestCase
{
    /**
     * @throws GuzzleException
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
