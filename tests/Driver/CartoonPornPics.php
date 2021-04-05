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

class CartoonPornPics extends TestCase
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
        $url = 'http://www.cartoonporn.pics/fr/galleries/cumming-inside-mommy-s-hole-vol-2-hentai-part-9#&gid=1&pid=1';
        $driver = new \Yamete\Driver\CartoonPornPics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(129, count($driver->getDownloadables()));
    }
}
