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
        $this->prepareLinks($this->sUrl);
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

    private function prepareLinks($sUrl)
    {
        $oParser = $this->getDomParser()->load($this->getBody($sUrl));
        foreach ($oParser->find('a.c-tile') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sDomain = self::DOMAIN;
            $sHref = $oLink->getAttribute('href');
            if (!$sHref) {
                continue;
            }
            $sLink = "https://www.$sDomain$sHref";
            if (!preg_match('~/[0-9]+$~', $sLink)) {
                $this->prepareLinks($sLink);
                continue;
            }
            foreach ($oParser->find('.image img.lazyload') as $oImg) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sFilename = str_replace('/th/', '/fm/', "https://www.$sDomain" . $oImg->getAttribute('data-src'));
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                    . str_pad(count($this->aReturn) + 1, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $this->aReturn[$sPath] = $sFilename;
            }
            return;
        }
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
