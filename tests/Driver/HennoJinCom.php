<?php

namespace YameteTests\Driver;


class HennoJinCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hennojin.com/home/manga/[Blue-Bean-(Kaname-Aomame)]-C2lemon@V.c2-(CODE-GEASS-Lelouch-of-the-Rebellion)-[English]-[Digital]/';
        $driver = new \Yamete\Driver\HennoJinCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadRead()
    {
        $url = 'https://hennojin.com/home/manga-reader/?manga=(SC2016%20Winter)%20[ASTRONOMY%20(SeN)]%20Kasou%20Juhou%20(Utawarerumono%20Itsuwari%20no%20Kamen)%20[English]&view=page';
        $driver = new \Yamete\Driver\HennoJinCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
