<?php


namespace Yamete\Driver;

use Yamete\DriverAbstract;

class MangahubIo extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangahub.io';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(manga|chapter)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables(): array
    {
        /**
         * @var \iterator $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oPages
         */
        $sStartUrl = 'https://' . self::DOMAIN;
        $sUrl = $sStartUrl . '/manga/' . $this->aMatches['album'];
        $oResponse = $this->getClient()->get($sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResponse->getBody())->find('.tab-content li a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oLink) {
            if (strpos($oLink->getAttribute('href'), '/chapter/') === false) {
                continue;
            }
            $oResponse = $this->getClient()->get($oLink->getAttribute('href'));
            $oPages = $this->getDomParser()->load((string)$oResponse->getBody())->find('.container-fluid img');
            foreach ($oPages as $oImage) {
                $sFilename = $oImage->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
}
