<?php

namespace Yamete\Driver;

class Hentai4Manga extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai4manga.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/hentai_manga/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $iNbPage = count(
            $this->getDomParser()->load((string)$oRes->getBody(), ['cleanupInput' => false])->find('#page div')
        ) - 2;
        $this->parse($this->sUrl, $aReturn);
        if ($iNbPage > 1) {
            for ($page = 2; $page <= $iNbPage; $page++) {
                $sUrl = substr($this->sUrl, 0, strlen($this->sUrl) - 1);
                $this->parse("${sUrl}_p${page}/", $aReturn);
            }
        }
        return $aReturn;
    }

    private function parse($sUrl, array &$aReturn)
    {
        foreach ($this->getDomParser()->loadFromUrl($sUrl, ['cleanupInput' => false])->find('#thumblist a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $sCurrentImg = 'http://' . self::DOMAIN . $oLink->getAttribute('href');
            $oImg = $this->getDomParser()->loadFromUrl($sCurrentImg, ['cleanupInput' => false])
                ->find('#innerContent div a img')[0];
            $sFilename = 'http://' . self::DOMAIN . $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(count($aReturn) + 1, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
