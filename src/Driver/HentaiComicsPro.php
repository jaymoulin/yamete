<?php

namespace Yamete\Driver;

class HentaiComicsPro extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaicomics.pro';

    public function canHandle()
    {
        return preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-'])
                . '/[^/]+/galleries/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.part-select option') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sUrl = 'http://www.' . self::DOMAIN . $oLink->getAttribute('value');
            foreach ($this->getDomParser()->load($sUrl)->find('.portfolio-normal-width figure a') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = $oImg->getAttribute('data-img') . $oImg->getAttribute('data-ext');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 4, '0', STR_PAD_LEFT)
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
