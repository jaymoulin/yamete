<?php

namespace YameteTests\Driver;


class VercomicsPorno extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadFoster()
    {
        $url = 'https://vercomicsporno.com/mac-primera-vez-exclusiva-accel-art';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(13, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadBowsette()
    {
        $url = 'https://vercomicsporno.com/english-bowsette-rescue-original-vcp';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadBroly()
    {
        $url = 'https://vercomicsporno.com/english-brolys-room-accel-artexclusivo';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadTinkerbell()
    {
        $url = 'https://vercomicsporno.com/milftoon-tinkerfuck-traduccion-exclusiva';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadRaven()
    {
        $url = 'https://vercomicsporno.com/zillionaire-luck-less-traduccion-exclusiva';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(41, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadMilf()
    {
        $url = 'https://vercomicsporno.com/dat-milf';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(42, count($driver->getDownloadables()));
    }
}
