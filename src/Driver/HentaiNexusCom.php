<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;


class HentaiNexusCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentainexus.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(view|read)/(?<album>[0-9]+)~',
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
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var AbstractNode $oImg
         */
        $this->sUrl = 'https://' . self::DOMAIN . '/read/' . $this->aMatches['album'];
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $aMatches = [];
        if (!preg_match('~initReader\("([^"]+)~', (string)$oResult->getBody(), $aMatches)) {
            return [];
        }
        $aJson = $this->getJson($aMatches[1]);
        $index = 0;
        $aReturn = [];
        foreach ($aJson['pages'] as $sFilename) {
            $sFilename = substr($sFilename, 0, strpos($sFilename, '?'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    /**
     * Get assets from initReader
     * @param string $sToDecode
     * @return array
     */
    private function getJson(string $sToDecode): array
    {
        $baseDecoded = base64_decode($sToDecode);
        $baseDecodedLength = strlen($baseDecoded);
        $_0x204508 = 0x0;
        $_0x3102fa = [];
        $_0x3e2b5d = [];
        for ($tabOfKey = 0x2; count($_0x3e2b5d) < 0x10; ++$tabOfKey) {
            if (!$_0x3102fa[$tabOfKey]) {
                $_0x3e2b5d[] = $tabOfKey;
                for ($_0x1a7e37 = $tabOfKey << 0x1; $_0x1a7e37 <= 256; $_0x1a7e37 += $tabOfKey) $_0x3102fa[$_0x1a7e37] = true;
            }
        }

        for ($tabOfKey = 0x0; $tabOfKey < 64; $tabOfKey++) {
            $_0x204508 = $_0x204508 ^ ord($baseDecoded[$tabOfKey]);
            for ($_0x1a7e37 = 0x0; $_0x1a7e37 < 0x8; $_0x1a7e37++) $_0x204508 = $_0x204508 & 0x1 ? $_0x204508 >> 0x1 ^ 0xc : $_0x204508 >> 0x1;
        }
        $_0x204508 = $_0x204508 & 0x7;

        for ($tabOfKey = [], $_0x17bc47 = 0x0, $jsonToParse = '', $_0x47a1c9 = 0x0; $_0x47a1c9 < 256; $_0x47a1c9++) {
            $tabOfKey[$_0x47a1c9] = $_0x47a1c9;
        }
        for ($_0x47a1c9 = 0x0; $_0x47a1c9 < 256; $_0x47a1c9++) {
            $_0x17bc47 = ($_0x17bc47 + $tabOfKey[$_0x47a1c9] + ord($baseDecoded[$_0x47a1c9 % 64])) % 256;
            $_0x734e1c = $tabOfKey[$_0x47a1c9];
            $tabOfKey[$_0x47a1c9] = $tabOfKey[$_0x17bc47];
            $tabOfKey[$_0x17bc47] = $_0x734e1c;
        }
        for ($_0x517f5a = $_0x3e2b5d[$_0x204508], $_0xcd3dde = 0x0, $_0x206008 = 0x0, $_0x47a1c9 = 0x0, $_0x17bc47 = 0x0, $charAt64Inc = 0x0; $charAt64Inc + 64 < $baseDecodedLength; $charAt64Inc++) {
            $_0x47a1c9 = ($_0x47a1c9 + $_0x517f5a) % 256;
            $_0x17bc47 = ($_0x206008 + $tabOfKey[($_0x17bc47 + $tabOfKey[$_0x47a1c9]) % 256]) % 256;
            $_0x206008 = ($_0x206008 + $_0x47a1c9 + $tabOfKey[$_0x47a1c9]) % 256;
            $_0x734e1c = $tabOfKey[$_0x47a1c9];
            $tabOfKey[$_0x47a1c9] = $tabOfKey[$_0x17bc47];
            $tabOfKey[$_0x17bc47] = $_0x734e1c;
            $_0xcd3dde = $tabOfKey[($_0x17bc47 + $tabOfKey[($_0x47a1c9 + $tabOfKey[($_0xcd3dde + $_0x206008) % 256]) % 256]) % 256];
            $jsonToParse .= chr(ord($baseDecoded[$charAt64Inc + 64]) ^ $_0xcd3dde);
        }
        return \GuzzleHttp\json_decode($jsonToParse, true);
    }
}
