<?php

namespace YameteTests\Driver;


class VercomicsPorno extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://vercomicsporno.com/mac-primera-vez-exclusiva-accel-art';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
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
