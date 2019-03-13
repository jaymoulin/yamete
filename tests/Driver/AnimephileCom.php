<?php

namespace YameteTests\Driver;


class AnimephileCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.animephile.com/hentai-doujinshi/bleach-dj-a-dangerous-weapon-known-as-a-school-uniform.html?ch=A+Dangerous+Weapon+Known+as+a+School+Uniform&mpg=2';
        $driver = new \Yamete\Driver\AnimephileCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(46, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadSerie()
    {
        $url = 'http://www.animephile.com/yuri/oniisama-e.html';
        $driver = new \Yamete\Driver\AnimephileCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(463, count($driver->getDownloadables()));
    }
}
