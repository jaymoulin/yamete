<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiXXXPicCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hentaixxxpic.com/galleries/tufus-the-fuckum-family-annie-gets-good-spanking#&gid=1&pid=1';
        $driver = new \Yamete\Driver\HentaiXXXPicCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
