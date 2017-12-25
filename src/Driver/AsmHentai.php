<?php

namespace Yamete\Driver;

class AsmHentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'asmhentai.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/g/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()
            ->request('GET', 'https://' . self::DOMAIN . '/gallery/' . $this->aMatches['album'] . '/1/');
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#jumpto_down option') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            if (empty($oLink->getAttribute('value'))) {
                continue;
            }
            $sLink = 'https://' . self::DOMAIN . $oLink->getAttribute('value');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#img img') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = 'https:' . $oImg->getAttribute('src');
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
