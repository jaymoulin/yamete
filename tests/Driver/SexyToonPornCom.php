<?php

namespace YameteTests\Driver;


class SexyToonPornCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.sexytoonporn.com/galleries/sweetcheeks-beach-bunnies';
        $driver = new \Yamete\Driver\SexyToonPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
