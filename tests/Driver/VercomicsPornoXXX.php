<?php

namespace YameteTests\Driver;


class VercomicsPornoXXX extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://vercomicsporno.xxx/raton-de-laboratorio-zoosexo-animal/';
        $driver = new \Yamete\Driver\VercomicsPornoXXX();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(19, count($driver->getDownloadables()));
    }
}
