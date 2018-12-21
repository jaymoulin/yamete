<?php

namespace YameteTests\Driver;


class CartoonPicsPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonpicsporn.com/content/nettori-netorare-milf-next-door-teaches-young-man/index.html';
        $driver = new \Yamete\Driver\CartoonPicsPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }
}
