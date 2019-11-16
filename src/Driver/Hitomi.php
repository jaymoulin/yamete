<?php

namespace Yamete\Driver;

class Hitomi extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hitomi.la';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/galleries/(?<album>[^.]+).html$~',
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
        $sAlbum = $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', "https://ltn.hitomi.la/galleries/${sAlbum}.js");
        $aReturn = [];
        $index = 0;
        $sJson = str_replace('var galleryinfo = ', '', (string)$oRes->getBody());
        foreach (\GuzzleHttp\json_decode($sJson, true) as $aItem) {
            foreach (['a', 'b', 'c'] as $cCdn) {
                /**
                 * @var \PHPHtmlParser\Dom\HtmlNode $oImg
                 */
                $sFilename = "https://${cCdn}a." . self::DOMAIN . "/galleries/${sAlbum}/${aItem['name']}";
                $oRes = $this->getClient()->request('GET', $sFilename, ["http_errors" => false]);
                if ($oRes->getStatusCode() !== 200) {
                    continue;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
                break;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(['headers' => ['Referer' => $this->sUrl]]);
    }
}
