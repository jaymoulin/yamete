<?php

namespace YameteTests\Driver;


class CartoonTumblrPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoontumblrporn.com/content/hot-sexy-sissy-femboy-toons-2/index.html';
        $driver = new \Yamete\Driver\CartoonTumblrPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
