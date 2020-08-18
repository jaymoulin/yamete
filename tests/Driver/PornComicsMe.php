<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PornComicsMe extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.porncomics.me/galleries/jab-comix-ay-papi-11-part-2?code=MjcxeDN4NDE2NDc=#&gid=1&pid=1';
        $driver = new \Yamete\Driver\PornComicsMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
