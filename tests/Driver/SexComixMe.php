<?php

namespace YameteTests\Driver;


class SexComixMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.sexcomix.me/galleries/saving-princess-marco-part-3?code=MjUxeDF4NzI2ODI=#&gid=1&pid=1';
        $driver = new \Yamete\Driver\SexComixMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
