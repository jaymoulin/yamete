<?php

namespace Yamete\Driver;

class HentaiRulesNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentairules.net';

    public function canHandle()
    {
        //http://www.hentairules.net/galleries4/index.php?/category/533
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) .
            ')/galleries(?<gallery>[0-9]+)/index\.php\?/category/(?<album>[0-9]+)$~',
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
        $iNbPage = count($this->getDomParser()->load((string)$oRes->getBody())->find('.navigationBar > a')) + 1;
        if (!$iNbPage) {
            $iNbPage = 1;
        }
        $sBaseUrl = 'http://www.' . self::DOMAIN . '/galleries' . $this->aMatches['gallery'];
        for ($page = 0; $page < $iNbPage; $page++) {
            $sUrl = $sBaseUrl . '/index.php?/category/' . $this->aMatches['album'] . '/start-' . $page . '00';
            foreach ($this->getDomParser()->loadFromUrl($sUrl)->find('.thumbnails li a') as $oLink) {
                /**
                 * @var \DOMElement $oLink
                 * @var \DOMElement $oImg
                 */
                $sUrl = $sBaseUrl . '/' . $oLink->getAttribute('href');
                $oImg = $this->getDomParser()->loadFromUrl($sUrl)->find('#theImage img')[0];
                $sFilename = $sBaseUrl . '/' . $oImg->getAttribute('src');
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
