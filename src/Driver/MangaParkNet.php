<?php

namespace Yamete\Driver;

class MangaParkNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangapark.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^/?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aChapters
         */
        $this->sUrl = "https://{$this->getDomain()}/{$this->aMatches['category']}/{$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody(), ['cleanupInput' => false])->find('ul.chapter a.ch');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        foreach ($aChapters as $oLink) {
            $sChapUrl = substr("https://{$this->getDomain()}{$oLink->getAttribute('href')}", 0, -2);
            $oRes = $this->getClient()->request('GET', $sChapUrl);
            if (!preg_match('~var _load_pages = (?<json>[^;]+)~', (string)$oRes->getBody(), $aMatch)) {
                continue;
            }
            foreach(\GuzzleHttp\json_decode($aMatch['json'], true) as $aPage) {
                $sFilename = $aPage['u'];
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(
            [
                'headers' => ['Cookie' => 'set=theme=1&h=1'],
            ]
        );
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
