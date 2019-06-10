<?php

namespace YameteTests\Driver;


class ThreeXPornComicsNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://3xporncomics.net/all-comics/how-to-make-friends-by-matemi/';
        $driver = new \Yamete\Driver\ThreeXPornComicsNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }

}
