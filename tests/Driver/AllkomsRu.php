<?php

namespace YameteTests\Driver;


class AllkomsRu extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://allkoms.ru/minnano-av/252/twisted-tales-porn-comics';
        $driver = new \Yamete\Driver\AllkomsRu();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(17, count($driver->getDownloadables()));
    }
}
