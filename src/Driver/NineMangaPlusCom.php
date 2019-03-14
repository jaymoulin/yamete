<?php

namespace Yamete\Driver;

use PHPHtmlParser\Dom\AbstractNode;

class NineMangaPlusCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '9mangaplus.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/(manga|chapter)/(?<album>[^/]+)/~',
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
         * @var AbstractNode $oImg
         * @var AbstractNode $oChapter
         * @var \Traversable $oChapters
         */
        $aReturn = [];
        $i = 0;
        $this->sUrl = "https://{$this->getDomain()}/manga/{$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('ul.episodes a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        foreach ($aChapters as $oChapter) {
            $oRes = $this->getClient()->request('GET', "https://{$this->getDomain()}{$oChapter->getAttribute('href')}");
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.row .info img.docimg') as $oImg) {
                if (!preg_match('~url=(?<filename>.+)~', $oImg->getAttribute('src'), $aMatch)) {
                    continue;
                }
                $sFilename = $aMatch['filename'];
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i, 5, '0', STR_PAD_LEFT)
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
