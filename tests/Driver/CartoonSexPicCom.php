<?php

namespace YameteTests\Driver;


class CartoonSexPicCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://cartoonsexpic.com/content/cartoons-women-who-love-it-when-you-cum-inside-them/index.html';
        $driver = new \Yamete\Driver\CartoonSexPicCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(39, count($driver->getDownloadables()));
    }
}
