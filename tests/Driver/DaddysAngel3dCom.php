<?php

namespace YameteTests\Driver;


class DaddysAngel3dCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://galleries.daddysangel3d.com/3d-daddys-61-whole-family/index.html';
        $driver = new \Yamete\Driver\DaddysAngel3dCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }

    public function testDownloadFromImg()
    {
        $url = 'http://galleries.daddysangel3d.com/pics/061/010.jpg';
        $driver = new \Yamete\Driver\DaddysAngel3dCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
