<?php

namespace Yamete\Driver;

class HentaiIdTv extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai-id.tv';

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function canHandle(): bool
    {
        if (preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . ')/(?<album>[^/]+)/$~',
            $this->sUrl
        )) {
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            /* @var \PHPHtmlParser\Dom\AbstractNode $oLink */
            $oLink = $this->getDomParser()->load((string)$oRes->getBody())->find('.mm2 a')[0];
            if (preg_match('~\?s=(?<url>.+)~', $oLink->getAttribute('href'), $aMatch)) {
                $this->sUrl = $aMatch['url'];
            }
        }
        $sMatch = '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-'])
            . ')/manga\.php\?id=(?<album>[0-9]+)~';
        return (bool)preg_match($sMatch, $this->sUrl, $this->aMatches);
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
        $sBaseUrl = "https://{$this->getDomain()}/manga.php?id={$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', $sBaseUrl);
        $aReturn = [];
        $index = 0;
        $sSelector = '#inlineFormCustomSelect option';
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sSelector) as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sUrl = "{$sBaseUrl}&p={$oLink->getAttribute('value')}";
            $oRes = $this->getClient()->request('GET', $sUrl);
            $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('img.img-m2')[0];
            $sFilename = $oImg->getAttribute('src');
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
