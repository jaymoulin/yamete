<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class ReadmOrg extends DriverAbstract
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
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var AbstractNode[] $aChapters
         * @var AbstractNode $oImg
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
                $sSrc = $oImg->getAttribute('src');
                $sFilename = strpos('http', $sSrc) === false ? 'https://' . self::DOMAIN . $sSrc : $sSrc;
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
