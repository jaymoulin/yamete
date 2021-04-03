<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class EHentai extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'e-hentai.org';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<mode>s|g)/([^/]+)/(?<album>[^/-]+)~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        if ($this->aMatches['mode'] == 's') {
            $sHref = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#i5 a')[0]->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sHref);
        }
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.gdtm a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             * @var AbstractNode $oImg
             */
            $oImg = $this->getDomParser()
                ->loadStr((string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody())
                ->find('#i3 img');
            $sFilename = $oImg->getAttribute('src');
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad($index++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
