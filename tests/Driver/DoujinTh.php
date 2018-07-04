<?php

namespace YameteTests\Driver;


class DoujinTh extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://doujin-th.com/forum/index.php?topic=11249.0';
        $driver = new \Yamete\Driver\DoujinTh();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
