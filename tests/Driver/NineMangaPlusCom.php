<?php

namespace YameteTests\Driver;


class NineMangaPlusCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://9mangaplus.com/chapter/imouto-android/391554';
        $driver = new \Yamete\Driver\NineMangaPlusCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
