<?php

namespace YameteTests\Driver;


class WarcraftPornPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.warcraftporn.pro/galleries/dwarf-vs-dwarf-1';
        $driver = new \Yamete\Driver\WarcraftPornPro();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
