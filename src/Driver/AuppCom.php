<?php

namespace Yamete\Driver;

use Yamete\DriverAbstract;

class AuppCom extends DriverAbstract
{
    private $aMatches;
    const DOMAIN = 'a-upp.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(?<lang>[a-z]{2}\.)?(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-'])
            . ')/s/(?<album>[0-9]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    protected function getDomain(): string
    {
        return self::DOMAIN;
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        $sBody = (string)$oRes->getBody();
        foreach ($this->getDomParser()->load($sBody)->find('#thumbnail-container .lazyload') as $oImg) {
            $sFilename = 'https://a.comicstatic.icu' . $oImg->getAttribute('data-src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename(preg_replace('~\?(.*)$~', '', $sFilename));
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
