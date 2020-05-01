<?php

namespace YameteTests\Driver;


class DoujinshiHentaiCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://doujinshihentai.com/manga/index.php/Strike%20Witches/Hokyuubusshi%20501';
        $driver = new \Yamete\Driver\DoujinshiHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
