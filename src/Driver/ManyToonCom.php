<?php

namespace Yamete\Driver;

class ManyToonCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'manytoon.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comic/(?<album>[^/]+)~',
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
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oChapter
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $sUrl = 'https://' . self::DOMAIN . '/comic/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('li.wp-manga-chapter a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oChapter) {
            $oRes = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.reading-content img') as $oImg) {
                $sFilename = trim($oImg->getAttribute('src'));
                $iPos = strpos($sFilename, '?');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename(substr($sFilename, 0, $iPos));
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
