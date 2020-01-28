<?php

namespace Yamete\Driver;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Client;

class BatoTo extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'bato.to';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/series/(?<album>[0-9]+)$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oLink
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResult->getBody())->find('a.chapt');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oLink->getAttribute('href'));
            $sRegexp = '~var images = ([^;]+);~';
            $aMatches = [];
            if (!preg_match($sRegexp, (string)$oResult->getBody(), $aMatches)) {
                continue;
            }
            foreach (\GuzzleHttp\json_decode($aMatches[1], true) as $sFilename) {
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
