<?php

namespace YameteTests\Driver;


class Comicsmanics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.comicsmanics.com/bad-boss-3-y3df/';
        $driver = new \Yamete\Driver\Comicsmanics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(79, count($driver->getDownloadables()));
    }

    public function testNewFormatDownload()
    {
        $url = 'http://www.comicsmanics.com/milftoon-lemonade-01-incest-comix/';
        $driver = new \Yamete\Driver\Comicsmanics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
