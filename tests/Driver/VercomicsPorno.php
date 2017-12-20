<?php

namespace YameteTests\Driver;


class VercomicsPorno extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://vercomicsporno.com/mac-primera-vez-exclusiva-accel-art';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
