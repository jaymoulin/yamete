<?php

namespace YameteTests\Driver;


class MangaBobCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangabob.com/manga/greatest-sword-immortal/';
        $driver = new \Yamete\Driver\MangaBobCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(132, count($driver->getDownloadables()));
    }
}
