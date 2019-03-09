<?php

namespace YameteTests\Driver;


class PornHugoCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.pornhugo.com/album/4336545/wreck-it-ralph';
        $driver = new \Yamete\Driver\PornHugoCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(16, count($driver->getDownloadables()));
    }
}
