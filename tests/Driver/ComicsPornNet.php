<?php

namespace YameteTests\Driver;


class ComicsPornNet extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.comicsporn.net/fr/galleries/lizard-orbs-1-the-invasion-part-2';
        $driver = new \Yamete\Driver\ComicsPornNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
