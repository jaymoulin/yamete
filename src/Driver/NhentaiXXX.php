<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class NhentaiXXX extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'nhentai.xxx';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/g/(?<album>[0-9]+)/?~',
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
        $sUrl = 'https://' . self::DOMAIN . '/g/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        $aFound = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gallerythumb img') as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            if (!$sFilename || isset($aFound[$sFilename])) {
                continue;
            }
            $aFound[$sFilename] = true;
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
