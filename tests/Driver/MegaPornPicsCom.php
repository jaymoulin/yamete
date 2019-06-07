<?php

namespace YameteTests\Driver;


class MegaPornPicsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://megapornpics.com/disney-wreck-it-ralph-hentai/';
        $driver = new \Yamete\Driver\MegaPornPicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23, count($driver->getDownloadables()));
    }
}
