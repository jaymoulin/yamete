<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PorncomixOnline extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.porncomixonline.net/xxxcomics/ilikemy-friend/';
        $driver = new \Yamete\Driver\PorncomixOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadNew()
    {
        $url = 'https://www.porncomixonline.net/xxxcomics/the-earth-chapter-1/';
        $driver = new \Yamete\Driver\PorncomixOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(31, count($driver->getDownloadables()));
    }
}
