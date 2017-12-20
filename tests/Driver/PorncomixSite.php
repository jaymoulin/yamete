<?php

namespace YameteTests\Driver;


class PorncomixSite extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://porncomix.site/linart-unexpected-surprise-porncomics/';
        $driver = new \Yamete\Driver\PorncomixSite();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
