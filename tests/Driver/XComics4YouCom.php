<?php

namespace YameteTests\Driver;


class XComics4YouCom extends \PHPUnit\Framework\TestCase
{
    public function testDownloadPlusCollection()
    {
        $url = 'http://www.xcomics4you.com/2015/Canopri-Comic/Canopri-Comic-Vol.22/image0020.html';
        $driver = new \Yamete\Driver\XComics4YouCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }

    public function testDownloadHtml()
    {
        $url = 'http://www.xcomics4you.com/2015/shinngeki_vol01/image0019.html';
        $driver = new \Yamete\Driver\XComics4YouCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(19, count($driver->getDownloadables()));
    }
}
