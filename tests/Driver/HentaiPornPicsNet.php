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

class HentaiPornPicsNet extends TestCase
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
        $url = 'http://www.hentaipornpics.net/galleries/-comic1-6-n2jirai-nimu-tsukihi-chan-ni-wa-naisho-de-keeping-secrets-from-tsukihi-chan-nisemonogatari-sharpie-translations?code=MTczeDM1eDQ3MDI=#&gid=1&pid=1';
        $driver = new \Yamete\Driver\HentaiPornPicsNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
