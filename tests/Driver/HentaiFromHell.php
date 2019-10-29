<?php

namespace YameteTests\Driver;


class HentaiFromHell extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaifromhell.org/gallery3/(C95)%20[Renai%20Mangaka%20(Naruse%20Hirofumi)]%20Genzai%20no%20Kubiki%20(Granblue%20Fantasy)%20[English]%20[Tigoris%20Translates].html';
        $driver = new \Yamete\Driver\HentaiFromHell();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
