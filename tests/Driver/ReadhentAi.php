<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ReadhentAi extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://readhent.ai/manga/seitokaichou-mitsuki-student-council-president-mitsuki';
        $driver = new \Yamete\Driver\ReadhentAi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(190, count($driver->getDownloadables()));
    }
}
