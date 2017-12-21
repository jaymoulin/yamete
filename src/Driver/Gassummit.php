<?php

namespace Yamete\Driver;

class Gassummit extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'gassummit.ru';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/[^/]+/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl, ['headers' => ['User-Agent' => self::USER_AGENT]]);
        $aReturn = [];
        $i = 0;
        $sPageSelector = '#topn span';
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.readchap') as $oChapter) {
            /**
             * @var \DOMElement $oChapter
             * @var \PHPHtmlParser\Dom\AbstractNode $oSpan
             */
            $sUrl = trim($oChapter->getAttribute('href'));
            $oRes = $this->getClient()->request('GET', $sUrl, ['headers' => ['User-Agent' => self::USER_AGENT]]);
            $oSpan = $this->getDomParser()->load((string)$oRes->getBody())->find($sPageSelector, 2);
            foreach ($oSpan->find('option') as $oLink) {
                /**
                 * @var \DOMElement $oLink
                 * @var \DOMElement $oImg
                 */
                $oImg = $this->getDomParser()->loadFromUrl($oLink->getAttribute('value'))->find('#con img')[0];
                $sFilename = str_replace(' ', '%20', trim($oImg->getAttribute('src')));
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                    . str_pad($i++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $aReturn[$sPath] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
