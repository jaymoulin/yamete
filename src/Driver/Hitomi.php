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
        $oRes = $this->getClient()->request('GET', str_replace('/galleries/', '/reader/', $this->sUrl));
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.img-url') as $oImg) {
            foreach (['a', 'b', 'c'] as $cCdn) {
                /**
                 * @var \PHPHtmlParser\Dom\HtmlNode $oImg
                 */
                $sReplace = '//' . $cCdn . 'a.';
                $sFilename = 'https:' . str_replace(['//g.', '//i.'], $sReplace, $oImg->innerhtml);
                $oRes = $this->getClient()->request('GET', $sFilename, ["http_errors" => false]);
                if ($oRes->getStatusCode() === 403 or $oRes->getStatusCode() === 404) {
                    continue;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
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
