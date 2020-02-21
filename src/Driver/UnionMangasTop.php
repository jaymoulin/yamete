<?php

namespace Yamete\Driver;


class UnionMangasTop extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'unionmangas.top';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        if (!preg_match_all('~a href="([^"]+)"~', (string)$oResult->getBody(), $aMatches)) {
            return [];
        }
        krsort($aMatches[1]);
        $aReturn = [];
        $index = 0;
        foreach ($aMatches[1] as $sLink) {
            if (strpos($sLink, 'leitor') === false) {
                continue;
            }
            $oResult = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oResult->getBody())->find('img.img-manga') as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                if (strpos($sFilename, 'http') === false) {
                    $sFilename = 'https:' . $sFilename;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
