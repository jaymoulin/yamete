<?php

namespace YameteTests\Driver;


class EggPornComicsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://eggporncomics.com/comics/20948/evil-rick-paranormal-activity';
        $driver = new \Yamete\Driver\EggPornComicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
