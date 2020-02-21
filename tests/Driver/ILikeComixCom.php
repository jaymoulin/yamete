<?php

namespace YameteTests\Driver;


class ILikeComixCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://ilikecomix.com/porncomix/batboys-phausto-batman/';
        $driver = new \Yamete\Driver\ILikeComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }

    public function testDownloadOtherCategory()
    {
        $url = 'https://ilikecomix.com/adultcomics/pet-girlfriend-3/';
        $driver = new \Yamete\Driver\ILikeComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(29, count($driver->getDownloadables()));
    }
}
