<?php

namespace YameteTests\Driver;


class YifferXyz extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://yiffer.xyz/Hotdogs';
        $driver = new \Yamete\Driver\YifferXyz();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(62, count($driver->getDownloadables()));
    }
}
