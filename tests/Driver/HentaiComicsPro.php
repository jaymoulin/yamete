<?php

namespace YameteTests\Driver;


class HentaiComicsPro extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hentaicomics.pro/en/galleries/the-simpsons-6-learning-with-mom?code=MTcweDF4Njg2Nw==#&gid=1&pid=1';
        $driver = new \Yamete\Driver\HentaiComicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
