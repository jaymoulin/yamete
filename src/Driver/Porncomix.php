<?php

namespace Yamete\Driver;

class Porncomix extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'porncomix.info';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^http://www\.' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gallery dt a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load((string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody())
                ->find('.single-post .attachment-image img');
            $sFilename = $oImg->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
