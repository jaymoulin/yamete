<?php

namespace YameteTests\Driver;


class CartoonPornPicNet extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.cartoonpornpic.net/pictures/unmerciful-ripe-blond-deepthroats-huge-dong-in-fuck-and-play-sweaty-session';
        $driver = new \Yamete\Driver\CartoonPornPicNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
