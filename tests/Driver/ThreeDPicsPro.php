<?php

namespace YameteTests\Driver;


class ThreeDPicsPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.3dpics.pro/pics/shiny-latex-anime/index.php';
        $driver = new \Yamete\Driver\ThreeDPicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
