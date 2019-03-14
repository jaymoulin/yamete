<?php

namespace YameteTests\Driver;


class MangazukiMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangazuki.me/manga/gokujou-drops/gokujou-drops-1/?style=list';
        $driver = new \Yamete\Driver\MangazukiMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(460, count($driver->getDownloadables()));
    }

}
