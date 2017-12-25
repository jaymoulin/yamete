<?php

namespace YameteTests\Driver;


class MangEroticaCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.mangerotica.com/manga-comics/[makoto_daikichi]serena_book_4_nightmare_again(pokemon)/index0001.php';
        $driver = new \Yamete\Driver\MangEroticaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }
}
