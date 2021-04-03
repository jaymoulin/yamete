<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class TwhentaiCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'twhentai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/hentai_(?<type>doujin|manga)/(?<album>[^/_]+)~',
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
         * @var AbstractNode $oLink
         * @var AbstractNode $oImage
         */
        $sUrl = 'https://' . self::DOMAIN . "/hentai_{$this->aMatches['type']}/{$this->aMatches['album']}/";
        $oResult = $this->getClient()->request('GET', $sUrl);
        $aMatches = [];
        $aReturn = [];
        $iChapter = 1;
        $index = 1;
        if (preg_match('~<li><a href=".+_p([^"]+)">末頁</a></li>~', (string)$oResult->getBody(), $aMatches)) {
            $iChapter = (int)$aMatches[1];
        }
        for ($iCurrentChapter = 1; $iCurrentChapter < ($iChapter + 1); $iCurrentChapter++) {
            $sUrl = 'https://' . self::DOMAIN
                . "/hentai_{$this->aMatches['type']}/{$this->aMatches['album']}_p${iCurrentChapter}/";
            if ($iCurrentChapter === 1) {
                $sUrl = 'https://' . self::DOMAIN . "/hentai_{$this->aMatches['type']}/{$this->aMatches['album']}/";
            }
            $oResult = $this->getClient()->request('GET', $sUrl);
            $oPages = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('a.thumbnail');
            foreach ($oPages as $oPage) {
                $oResult = $this->getClient()
                    ->request('GET', 'https://' . self::DOMAIN . $oPage->getAttribute('href'));
                $oImage = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('img.img-responsive')[0];
                $sFilename = $oImage->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
