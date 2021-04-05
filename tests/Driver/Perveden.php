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

class Perveden extends TestCase
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
        $url = 'https://www.perveden.com/en/en-manga/c91-candy-paddle-nemunemu-idol-prelude-the-idolmster-sidem-otokonoko-matome-hon-2013-2015-english-sw/1/1/';
        $driver = new \Yamete\Driver\Perveden();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
