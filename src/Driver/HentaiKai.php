<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class HentaiKai extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentaikai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var Traversable $oChapters
         * @var AbstractNode $oChapter
         * @var AbstractNode $oImg
         */
        $sUrl = 'https://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oChapter = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('.post-fotos a')[0];
        $index = 0;
        $aReturn = [];
        $oRes = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
        $sBody = (string)$oRes->getBody();
        $sRegExp = '~body:after\{content:([^;]+);display:none\}~';
        $aFound = [];
        if (!preg_match($sRegExp, $sBody, $aFound)) {
            return [];
        }
        $aMatches = [];
        if (!preg_match_all('~url\(([^)]+)\)~', $aFound[1], $aMatches)) {
            return [];
        }
        foreach ($aMatches[1] as $sFilename) {
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
