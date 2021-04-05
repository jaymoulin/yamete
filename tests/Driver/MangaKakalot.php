<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use PHPUnit\Framework\TestCase;

class MangaKakalot extends TestCase
{
    /**
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function testDownload()
    {
        $url = 'https://mangakakalot.com/read-ia6ir158504862497';
        $driver = new \Yamete\Driver\MangaKakalot();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(923, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function testDownloadChapter()
    {
        $url = 'https://mangakakalot.com/chapter/kawaiikereba_hentai_demo_suki_ni_natte_kuremasu_ka/chapter_8';
        $driver = new \Yamete\Driver\MangaKakalot();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
