<?php

namespace YameteTests\Driver;


class SexPornComicsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://sexporncomics.com/110/jungle-hell-part-2-hey-arnold-adult-comic-from-palcomix';
        $driver = new \Yamete\Driver\SexPornComicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
