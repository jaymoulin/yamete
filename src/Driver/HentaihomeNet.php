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

class HentaihomeNet extends DriverAbstract
{
    private const DOMAIN = 'hentaihome.net';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
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
        $oRes = $this->getClient()->request(
            'GET',
            implode('/', ['https://www.' . self::DOMAIN, $this->aMatches['album']])
        );
        $aReturn = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('a.fancybox img') as $oImg) {
            $sFilename = $oImg->getAttribute('src');
            $sFilename = str_starts_with($sFilename, 'http') ? $sFilename : 'https:' . $sFilename;
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

}
