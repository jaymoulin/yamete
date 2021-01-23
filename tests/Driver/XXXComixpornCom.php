<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class XXXComixpornCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.xxxcomixporn.com/pictures/familia-sacana-17-tufos';
        $driver = new \Yamete\Driver\XXXComixpornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(14, count($driver->getDownloadables()));
    }
}
