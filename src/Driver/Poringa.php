<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class Poringa extends DriverAbstract
{
    private const DOMAIN = 'poringa.net';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        $sMatch = '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.'])
            . ')/posts/hentai/(?<album_id>[^/?]+)/(?<album>[^.]+).html$~';
        return (bool)preg_match($sMatch, $this->sUrl, $this->aMatches);
    }

    /**
     * @return array
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.main-content-post img') as $oImg) {
            $sFilename = $oImg->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
