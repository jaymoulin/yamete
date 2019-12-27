<?php

namespace YameteTests\Driver;


class KastalkluchRu extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://kastalkluch.ru/xn--18-3qi1e6drb/115/adult-incest-comic-king-the-hill-bobby%D0%B2%D1%92%E2%84%A2s-fuck-hole-sfan-jad';
        $driver = new \Yamete\Driver\KastalkluchRu();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(16, count($driver->getDownloadables()));
    }
}
