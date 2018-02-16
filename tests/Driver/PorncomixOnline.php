<?php

namespace YameteTests\Driver;


class PorncomixOnline extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://www.porncomixonline.net/felsalamy-friends-mom/';
        $driver = new \Yamete\Driver\PorncomixOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
