<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class CartoonPornPics extends TestCase
{
    /**
     * @throws GuzzleException
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
