<?php

namespace YameteTests\Driver;


class CartoonSexPicCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonsexpic.com/content/sidney-2/index.html';
        $driver = new \Yamete\Driver\CartoonSexPicCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(33, count($driver->getDownloadables()));
    }
}
