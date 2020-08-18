<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class YuriIsmNet extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'yuri-ism.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/slide/(read|series)/(?<album>[^/]+)/~',
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
         * @var AbstractNode[] $oChapters
         */
        $this->sUrl = 'https://www.' . self::DOMAIN . "/slide/series/{$this->aMatches['album']}/";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('.title a');
        foreach ($oChapters as $oLink) {
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $aMatch = [];
            if (!preg_match('~var pages = (?<json>[^;]+)~', (string)$oRes->getBody(), $aMatch)) {
                continue;
            }
            foreach (\GuzzleHttp\json_decode($aMatch['json'], true) as $aPage) {
                $sFilename = $aPage['url'];
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
