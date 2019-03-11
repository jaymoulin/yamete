<?php

namespace YameteTests\Driver;


class PornComicsMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
