<?php

namespace Yamete\Driver;

class HentaiBeast extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaibeast.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/index\.php\?/category/(?<album>[^/]+)$~',
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
        $iNbPages = 1;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.navigationBar a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $iNbPages = (int)$oLink->innerHtml() ?: $iNbPages;
        }
        for ($iPage = 1; $iPage <= ($iNbPages ?: 1); $iPage++) {
            $oRes = $this->getClient()->request('GET', $this->sUrl . ($iPage > 1 ? '/start-' . (($iPage - 1)  * 10) : ''));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gthumb a') as $oLink) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oLink
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sUrl = 'https://' . self::DOMAIN . '/' . $oLink->getAttribute('href');
                $oRes = $this->getClient()->request('GET', $sUrl);
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#theImage img')[0];
                $sFilename = 'https://' . self::DOMAIN . '/' . $oImg->getAttribute('src');
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
