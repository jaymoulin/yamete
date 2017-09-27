<?php

namespace Yamete\Driver;

class HentaiboxNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaibox.net';

    public function canHandle()
    {
        return preg_match(
            '~^http://www\.' . strtr(self::DOMAIN, ['.' => '\.'])
                . '/hentai\-manga/[0-9]{2}_[0-9]{2}_(?<album>[^/])/(00)?$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        if (substr($this->sUrl, -3, 3) != '/00') {
            $this->sUrl .= '/00';
        }
        $oRes = $this->getClient()->request('GET', $this->sUrl);

        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('select["name=np2"] option') as $oOption) {
            /**
             * @var \DOMElement $oOption
             * @var \DOMElement[] $aImg
             */
            $aImg = $this->getDomParser()->load(
                (string)$this->getClient()
                    ->request(
                        'GET',
                        str_replace('/00', $oOption->getAttribute('value'), $this->sUrl)
                    )->getBody()
            )
                ->find('td > center > a > img');
            $sFilename = $aImg[0]->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
