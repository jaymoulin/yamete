<?php

namespace YameteTests\Driver;


class EighteenLHPlusCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://18lhplus.com/manga-silver-street-romantic.html';
        $driver = new \Yamete\Driver\EighteenLHPlusCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(48, count($driver->getDownloadables()));
    }
}
