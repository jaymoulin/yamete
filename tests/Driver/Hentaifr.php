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

class Hentaifr extends TestCase
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
        $url = 'https://hentaifr.net/forbidden-frontiers-vol-5-par-pokkuti-lecture-en-ligne/';
        $driver = new \Yamete\Driver\Hentaifr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
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
    public function testDownloadToBeSure()
    {
        $url = 'https://hentaifr.net/cait-x-vi-x-jinx-badcompzero-lecture-en-ligne/';
        $driver = new \Yamete\Driver\Hentaifr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
