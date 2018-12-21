<?php

namespace YameteTests\Driver;


class XXXMangaComixCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.xxxmangacomix.com/gallery/frozen-wedding-jitters.html';
        $driver = new \Yamete\Driver\XXXMangaComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
