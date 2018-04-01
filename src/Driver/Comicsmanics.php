<?php

namespace Yamete\Driver;

class Comicsmanics extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'comicsmanics.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
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
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.single-post img.size-full') as $oImg) {
            /**
             * @var \DOMElement $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            $sFilename = preg_match('~^https?://~', $sFilename)
                ? str_replace('https://', 'http://', $sFilename)
                : 'http://www.' . self::DOMAIN . $sFilename;
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        if (!$i) {
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.size-large') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = 'http://www.' . self::DOMAIN . $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
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
