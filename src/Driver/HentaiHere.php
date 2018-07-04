<?php

namespace Yamete\Driver;

class HentaiHere extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaihere.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/m/(?<album>[^/]+)/$~',
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
        $aReturn = [];
        $oRes = $this->getClient()->request('GET', $this->sUrl . '1/1/');
        $iNbChapter = count($this->getDomParser()->load((string)$oRes->getBody())->find('.dropdown ul.text-left li'));
        $i = 0;
        for ($iChapter = 1; $iChapter <= $iNbChapter; $iChapter++) {
            $oRes = $this->getClient()->request('GET', $this->sUrl . $iChapter . '/1/');
            $iNbPage = count($this->getDomParser()->load((string)$oRes->getBody())->find('#pageDropdown li'));
            for ($iPage = 1; $iPage <= $iNbPage; $iPage++) {
                $oRes = $this->getClient()->request('GET', $this->sUrl . $iChapter . '/' . $iPage . '/');
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#arf-reader-img')[0];
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
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
