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

class MangaReadCo extends TestCase
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
        $url = 'https://mangaread.co/manga/choppiri-ecchi-na-san-shimai-demo-oyome-san-ni-shitekuremasuka/';
        $driver = new \Yamete\Driver\MangaReadCo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
