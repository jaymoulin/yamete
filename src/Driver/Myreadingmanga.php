<?php

namespace Yamete\Driver;

class Myreadingmanga extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $iCurrentPage = 0;
    const DOMAIN = 'myreadingmanga.info';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    private function getImgListForBody(string $sBody): array
    {
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$sBody)->find('.content .entry-content img') as $oImg) {
            /** @var \PHPHtmlParser\Dom\AbstractNode $oImg */
            $sFilename = $oImg->getAttribute('src');
            if (strpos($sFilename, 'trans.gif') !== false) {
                continue;
            }
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$this->iCurrentPage, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oPageList = $this->getDomParser()->load((string)$oRes->getBody())->find('.pagination a');
        $aReturn = $this->getImgListForBody((string)$oRes->getBody());
        foreach ($oPageList as $oLink) {
            /** @var \PHPHtmlParser\Dom\AbstractNode $oLink */
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $aReturn = array_merge($aReturn, $this->getImgListForBody((string)$oRes->getBody()));
        }
        return $aReturn;
    }


    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
