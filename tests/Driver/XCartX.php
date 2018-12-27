<?php

namespace YameteTests\Driver;


class XCartX extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://xcartx.com/483-furry-incest-porn-comics.html';
        $driver = new \Yamete\Driver\XCartX();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(17, count($driver->getDownloadables()));
    }
}
