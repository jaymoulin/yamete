<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiSchoolCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaischool.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var AbstractNode $oIframe
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $oIframe = $this->getDomParser()->load((string)$oRes->getBody())->find('iframe#frame-id')[0];
        $sUrl = $oIframe->getAttribute('src');
        $oRes = $this->getClient()->request('GET', $sUrl);
        $iNbPage = count($this->getDomParser()->load((string)$oRes->getBody())->find('select.page-form option'));
        for ($index = 1; $index <= $iNbPage; $index++) {
            $sFilename = $sUrl . $index . '.jpg';
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
