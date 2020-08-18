<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class CartoonTumblrPornCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoontumblrporn.com/content/3d-0054-cartoons-hentai-foundry-gallery/index.html';
        $driver = new \Yamete\Driver\CartoonTumblrPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(44, count($driver->getDownloadables()));
    }
}
