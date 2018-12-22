<?php

namespace Yamete\Driver;

class Shentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'shentai.xyz';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.entry-content > p > a') as $oLink) {
            /* @var \PHPHtmlParser\Dom\AbstractNode $oLink */
            if (strpos($oLink->getAttribute('rel'), 'noopener') === false) {
                continue;
            }
            $oImg = $oLink->find('img')[0];
            /* @var \PHPHtmlParser\Dom\AbstractNode $oImg */
            $sFilename = str_replace('small', 'big', $oImg->getAttribute('src'));
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT) . '-'
                . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
