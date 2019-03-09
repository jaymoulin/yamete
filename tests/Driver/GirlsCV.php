<?php

namespace YameteTests\Driver;


class GirlsCV extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.girlscv.com/Gallery/7965999/Anime-Cartoon-Natalie';
        $driver = new \Yamete\Driver\GirlsCV();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(13, count($driver->getDownloadables()));
    }
}
