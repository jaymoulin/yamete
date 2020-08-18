<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class ACGXMangaCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'acgxmanga.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/h/(?<album>[0-9]+).html$~',
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
         * @var AbstractNode $oNbPage
         * @var AbstractNode $oImg
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $aNbPage = [];
        $index = 0;
        if (!preg_match('~>([0-9]+)</a> <a [^>]+>下一页</a>~', (string)$oRes->getBody(), $aNbPage)) {
            return [];
        }
        $iNbPage = (int)$aNbPage[1];
        for ($iPage = 1; $iPage <= $iNbPage; $iPage++) {
            $aMatch = [];
            $oRes = $this->getClient()->request('GET', str_replace('.html', '-' . $iPage . '.html', $this->sUrl));
            if (!preg_match('~<img.+src="([^"]+)"~', (string)$oRes->getBody(), $aMatch)) {
                continue;
            }
            $sFilename = $aMatch[1];
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename(preg_replace('~\?(.*)$~', '', $sFilename));
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
