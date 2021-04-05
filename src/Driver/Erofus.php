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

class Erofus extends DriverAbstract
{
    private const DOMAIN = 'erofus.com';
    private array $aMatches = [];
    private array $aReturn = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comics/(?<collection>[^/]+)/(?<album>[^/]+)~',
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
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws GuzzleException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function getLinks(string $sUrl): void
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.row-content a') as $oLink) {
            $sHref = $oLink->getAttribute('href');
            if (!$oLink->getAttribute('title')) {
                continue;
            }
            $this->getLinks(str_contains($sHref, '://') ? $sHref : 'https://www.' . self::DOMAIN . $sHref);
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oImg = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#picture-full img')[0];
        $sFilename = 'https://www.' . self::DOMAIN . $oImg->getAttribute('src');
        $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(count($this->aReturn) + 1, 5, '0', STR_PAD_LEFT)
            . '-' . basename($sFilename);
        $this->aReturn[$sBasename] = $sFilename;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
