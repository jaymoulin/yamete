<?php

namespace YameteTests\Driver;


class AvangardIvRu extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://avangard-iv.ru/pornwrap/252/twisted-tales-porn-comics';
        $driver = new \Yamete\Driver\AvangardIvRu();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(17, count($driver->getDownloadables()));
    }
}
