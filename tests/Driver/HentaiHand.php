<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use PHPUnit\Framework\TestCase;

class HentaiHand extends TestCase
{
    /**
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function testDownload()
    {
        $url = 'https://hentaihand.com/en/comic/art-jam-mitsumaro-taneshizume-no-miko-maki-no-san-taiiku-souko-no-nie-english-hennojin-digital';
        $driver = new \Yamete\Driver\HentaiHand();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
