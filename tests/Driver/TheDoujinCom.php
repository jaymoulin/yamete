<?php

namespace YameteTests\Driver;


class TheDoujinCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadMultiple()
    {
        $url = 'https://thedoujin.com/index.php/categories/242231';
        $driver = new \Yamete\Driver\TheDoujinCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(197, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadSimple()
    {
        $url = 'https://thedoujin.com/index.php/categories/242238';
        $driver = new \Yamete\Driver\TheDoujinCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(43, count($driver->getDownloadables()));
    }
}
