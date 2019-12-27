<?php

namespace Yamete\Driver;

class YifferXyz extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'yiffer.xyz';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \Traversable $oPages
         * @var \PHPHtmlParser\Dom\AbstractNode $oImgs
         */
        $sUrl = 'https://' . self::DOMAIN . '/api/comics/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aInfo = \GuzzleHttp\json_decode((string)$oRes->getBody(), true);
        if (!isset($aInfo['numberOfPages'])) {
            return [];
        }
        $aReturn = [];
        for ($index = 1; $index <= (int)$aInfo['numberOfPages']; $index++) {
            $sFilename = 'https://' . self::DOMAIN . '/comics/' . $this->aMatches['album'] .
                '/' . str_pad($index, 2, '0', STR_PAD_LEFT) . '.jpg';
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
