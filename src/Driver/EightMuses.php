<?php

namespace Yamete\Driver;

class EightMuses extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    const DOMAIN = '8muses.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/comi(x|cs)/album/(?<album>[^?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     */
    public function getDownloadables()
    {
        $this->aReturn = [];
        foreach ($this->getDomParser()->load($this->getBody($this->sUrl))->find('a.c-tile') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sHref = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oParser = $this->getDomParser()->load($this->getBody($sHref));
            if (isset($oParser->find('#imageHost')[0])) {
                $this->prepareLinks($oParser);
            } else {
                $oParser = $this->getDomParser()->load($this->getBody($sHref));
                foreach ($oParser->find('a.c-tile') as $oLinkImg) {
                    /**
                     * @var \DOMElement $oLinkImg
                     */
                    $sHref = 'https://www.' . self::DOMAIN . $oLinkImg->getAttribute('href');
                    $oParser = $this->getDomParser()->load($this->getBody($sHref));
                    if (isset($oParser->find('#imageHost')[0])) {
                        $this->prepareLinks($oParser);
                    }
                }
            }
        }
        return $this->aReturn;
    }

    /**
     * Retrieve body for specified url
     * @param string $sUrl
     * @return string
     * @throws
     */
    private function getBody($sUrl)
    {
        return (string)$this->getClient()->request('GET', $sUrl)->getBody();
    }

    private function prepareLinks(\PHPHtmlParser\Dom $oParser)
    {
        $sHost = $oParser->find('#imageHost')[0]->getAttribute('value');
        $sHost = $sHost ?: '//www.' . self::DOMAIN;
        $sName = $oParser->find('#imageName')[0]->getAttribute('value');
        $sFilename = "https:$sHost/image/fl/$sName";
        $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
            . str_pad(count($this->aReturn) + 1, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
        $this->aReturn[$sPath] = $sFilename;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
