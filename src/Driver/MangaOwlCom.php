<?php

namespace Yamete\Driver;

class MangaOwlCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangaowl.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/reader/(?<album>[^/?]+)/(?<reader>[^/?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 1;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#selectChapter option') as $oOption) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oOption
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $oRes = $this->getClient()->request('GET', trim($oOption->getAttribute('url')));
            $aChap = [];
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.owl-lazy') as $oImg) {
                $sFilename = $oImg->getAttribute('data-src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aChap[$sBasename] = $sFilename;
            }
            $aChap = array_reverse($aChap);
            $aReturn = array_merge($aReturn, $aChap);
        }
        return array_reverse($aReturn);;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
