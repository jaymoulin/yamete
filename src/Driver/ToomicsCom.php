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


class ToomicsCom extends DriverAbstract
{
    private const DOMAIN = 'toomics.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.'])
            . ')/(?<locale>[a-z]{2})/webtoon/episode/toon/(?<album>[0-9]+)$~',
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
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $sRegExp = '~Webtoon\.chkec\(this\);location\.href=\'([^\']+)\'~';
        $aUrls = [];
        if (!preg_match_all($sRegExp, (string)$oResult->getBody(), $aUrls)) {
            return [];
        }
        $index = 0;
        $aReturn = [];
        foreach ($aUrls[1] as $sUrl) {
            if (!str_contains($sUrl, '/ep/')) {
                continue;
            }
            $sUrl = 'https://' . self::DOMAIN . $sUrl;
            $oResult = $this->getClient()->request('GET', $sUrl);
            foreach ($this->getDomParser()->loadStr((string)$oResult->getBody())->find('#viewer-img img') as $oImg) {
                $sFilename = $oImg->getAttribute('data-original');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
