<?php

namespace Yamete\Driver;

class DoujinHentaiNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'doujinhentai.net';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            '/(?<category>[^/]+)/(?<album>[^/]+)/?~',
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
        $sUrl = "https://" . $this->getDomain() . '/' .
            implode('/', [$this->aMatches['category'], $this->aMatches['album']]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('ul.version-chap a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('div#all img') as $oImg) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sFilename = trim($oImg->getAttribute('data-src'));
                if (!$sFilename) {
                    continue;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
