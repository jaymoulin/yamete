<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class EighteenLHPlusCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '18lhplus.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga\-(?<album>[^.]+)\.html$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var Traversable $oChapters
         * @var AbstractNode $oChapter
         * @var AbstractNode $oImg
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResult->getBody())->find('a.chapter');
        $index = 0;
        $aReturn = [];
        foreach ($oChapters as $oChapter) {
            $oResult = $this->getClient()
                ->request('GET', 'https://' . self::DOMAIN . '/' . $oChapter->getAttribute('href'));
            foreach ($this->getDomParser()->load((string)$oResult->getBody())->find('img.chapter-img') as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return array_merge(array_slice($aReturn, -1, 1), array_slice($aReturn, 0, $index - 1));
    }
}
