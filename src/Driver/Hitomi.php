<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\HtmlNode;
use Yamete\DriverAbstract;

class Hitomi extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hitomi.la';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) .
            '/(?<category>cg|manga|doujinshi|gamecg|galleries)/(?<album>.+)\.html$~',
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
        $aMatch = [];
        preg_match('~(?<iAlbumId>[0-9]+)(\.html)?$~', $this->aMatches['album'], $aMatch);
        $iAlbumId = (int)$aMatch['iAlbumId'];
        $oRes = $this->getClient()->request('GET', "https://ltn.hitomi.la/galleries/${iAlbumId}.js");
        $aReturn = [];
        $index = 0;
        $sJson = str_replace('var galleryinfo = ', '', (string)$oRes->getBody());
        $aItems = \GuzzleHttp\json_decode($sJson, true);
        if (!isset($aItems['files'])) {
            return [];
        }
        foreach ($aItems['files'] as $aItem) {
            foreach (['a', 'b'] as $cCdn) {
                foreach (['a', 'b'] as $cCdn2) {
                    /**
                     * @var HtmlNode $oImg
                     */
                    $sHash = $aItem['hash'];
                    $sFolder = substr($aItem['hash'], -1, 1);
                    $sSubFolder = substr($aItem['hash'], -3, 2);
                    $bHasWebp = isset($aItem['haswebp']) && $aItem['haswebp'];
                    $sCategory = $bHasWebp ? 'webp' : 'images';
                    $sExt = $bHasWebp ? 'webp' : substr($aItem['name'], strrpos($aItem['name'], '.') + 1);
                    $sFilename = "https://${cCdn}${cCdn2}." . self::DOMAIN . "/$sCategory/$sFolder/$sSubFolder/${sHash}.$sExt";
                    $oRes = $this->getClient()->request('GET', $sFilename, ["http_errors" => false]);
                    if ($oRes->getStatusCode() !== 200) {
                        continue;
                    }
                    $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                        . '-' . basename($sFilename);
                    $aReturn[$sBasename] = $sFilename;
                    break 2;
                }
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Referer' => $this->sUrl]]);
    }
}
