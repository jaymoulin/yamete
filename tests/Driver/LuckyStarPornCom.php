<?php

namespace YameteTests\Driver;


class LuckyStarPornCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.luckystarporn.com/hentai2016/Onee-san_Hara_Mix/image000.php';
        $driver = new \Yamete\Driver\LuckyStarPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(116, count($driver->getDownloadables()));
    }
}
