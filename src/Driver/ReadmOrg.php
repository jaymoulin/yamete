<?php

namespace Yamete\Driver;

class ReadmOrg extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'readm.org';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
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
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . "/manga/{$this->aMatches['album']}");
        $aReturn = [];
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('#table-episodes-title a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        foreach ($aChapters as $oChapter) {
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oChapter->getAttribute('href'));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.ch-images img') as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename(preg_replace('~\?(.*)$~', '', $sFilename));
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
