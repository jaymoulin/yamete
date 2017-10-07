<?php

namespace Yamete\Driver;

class Hentaifr extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaifr.net';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/ngdoujinshishe\.php\?id=(?<album>[0-9]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $sPageUrl = 'http://' . self::DOMAIN . '/doujinshisheng.php?id=' . $this->aMatches['album'] . '&c=';
        $oRes = $this->getClient()->request('GET', $sPageUrl . '0');
        $aReturn = [];
        $i = 0;
        $oPageList = $this->getDomParser()->load((string)$oRes->getBody())->find('.ngpage-pagination td a');
        /** @var \DOMElement $oNbPage */
        foreach ($oPageList as $oNbPage); //goes to the end of the list
        if (preg_match('~&c=([0-9]+)~', $oNbPage->getAttribute('href'), $aMatches)) {
            for ($iPage = 0; $iPage <= $aMatches[1]; $iPage++) {
                $oRes = $this->getClient()->request('GET', $sPageUrl . $iPage);
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('.doujin-vote-bloc img')[0];
                /** @var \DOMElement $oImg */
                $sFilename = 'http://' . self::DOMAIN . $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
