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

class MyHentaiGallery extends TestCase
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
        $url = 'https://myhentaigallery.com/gallery/thumbnails/1458';
        $driver = new \Yamete\Driver\MyHentaiGallery();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(53, count($driver->getDownloadables()));
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
    public function testDownloadSpecial()
    {
        $url = 'https://myhentaigallery.com/gallery/thumbnails/14053';
        $driver = new \Yamete\Driver\MyHentaiGallery();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(21, count($driver->getDownloadables()));
    }
}
