<?php

namespace YameteTests\Driver;


class HentaiHighSchoolCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentai-high-school.com/2015/shinngeki_vol04/image0001.html';
        $driver = new \Yamete\Driver\HentaiHighSchoolCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
