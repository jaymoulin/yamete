<?php

namespace Yamete\Driver;

class MintManga extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mintmanga.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/(?<album>[^/]+)/~',
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
        $sBaseUrl = 'http://' . $this->getDomain();
        $oRes = $this->getClient()->request('GET', $sBaseUrl . '/' . $this->aMatches['album'] . '/vol1/1?mtr=1');
        $aReturn = [];
        $index = 0;
        $sSelector = '#chapterSelectorSelect option';
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sSelector) as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sChapterUrl = $sBaseUrl . $oLink->getAttribute('value');
            $oRes = $this->getClient()->request('GET', $sChapterUrl . '1?mtr=1');
            $sRegExp = '~rm_h.init\((?<json>[^\)]+)\)~';
            if (preg_match($sRegExp, (string)$oRes->getBody(), $aMatches)) {
                $sJsonClean = implode(',', array_slice(explode(',', trim($aMatches['json'])), 0, -2));
                foreach (explode('],[', $sJsonClean) as $sString) {
                    $sFilename = str_replace(['"', '\''], '', implode('', array_slice(explode(',', $sString), 1, 2)));
                    $sFilename = preg_replace('~\?.*$~', '', $sFilename);
                    $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                        . '-' . basename($sFilename);
                    $aReturn[$sBasename] = $sFilename;
                }
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
