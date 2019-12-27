<?php

namespace YameteTests\Driver;


class MangaTownCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangatown.com/manga/my_sister_has_amnesia_what_s_sex/';
        $driver = new \Yamete\Driver\MangaTownCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(53, count($driver->getDownloadables()));
    }
}
