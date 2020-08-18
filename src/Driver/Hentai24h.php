<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Hentai24h extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai24h.org';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/(?<album>[^/]+)/chap-[0-9]+.html$~',
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
        $aReturn = [];
        $index = 0;
        $iNbChap = count($this->getDomParser()->load((string)$oRes->getBody())->find('.chapter-nav a.btn-success')) / 2;
        for ($iChap = 1; $iChap <= $iNbChap; $iChap++) {
            /**
             * @var AbstractNode $oImg
             */
            $sUrl = "https://" . implode(
                    '/',
                    [$this->getDomain(), $this->aMatches['album'], 'chap-' . $iChap . '.html']
                );
            $oRes = $this->getClient()->request('GET', $sUrl);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.content-child p img') as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
