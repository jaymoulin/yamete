<?php

namespace Yamete\Driver;

class HentaiVN extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaivn.net';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^.]+).html$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        $sAccessor = '.listing td a';
        $oList = $this->getDomParser()->load((string)$oRes->getBody())->find($sAccessor);
        $iChapters = count($oList);
        foreach ($oList as $oLink) { //chapters
            /**
             * @var \DOMElement $oLink
             */
            $sLink = 'http://' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $oBody = $this->getDomParser()->load((string)$oRes->getBody());
            foreach ($oBody->find('#image img') as $oImg) { //images
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . $iChapters . DIRECTORY_SEPARATOR
                    . str_pad($i++, 5, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            --$iChapters;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
