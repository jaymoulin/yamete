<?php

namespace Yamete\Driver;

class NSOTennis extends \Yamete\DriverAbstract
{
    const DOMAIN = 'nsotennis.ru';
    private $aMatches = [];

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(x.)?(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
            ')/listmangahentai\.html/(?<category>[^/]+)/(?<album>[^/?]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $sUrl = "https://{$this->getDomain()}"
            . "/listmangahentai.html/{$this->aMatches['category']}/{$this->aMatches['album']}/";
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        $aChapters = iterator_to_array($this->getDomParser()->load((string)$oRes->getBody())->find('a.readchap'));
        krsort($aChapters);
        foreach ($aChapters as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oChapterLink
             * @var \PHPHtmlParser\Dom\InnerNode $oSelect
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sChapter = str_replace('/listmangahentai.html/ ', 'https:', trim($oLink->getAttribute('href')));
            $oRes = $this->getClient()->request('GET', $sChapter);
            $aItems = $this->getDomParser()->load((string)$oRes->getBody())->find('select');
            $oSelect = $aItems[count($aItems) - 1];
            foreach ($oSelect->getChildren() as $oChapterLink) {
                $sPageLink = trim($oChapterLink->getAttribute('value'));
                $oRes = $this->getClient()->request('GET', $sPageLink);
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#con img')[0];
                $sFilename = trim($oImg->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(['headers' => ['User-Agent' => 'chromium']]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
