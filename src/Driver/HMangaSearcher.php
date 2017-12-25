<?php

namespace Yamete\Driver;

use PhpParser\Node\Expr\ArrayItem;

class HMangaSearcher extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hmangasearcher.com';
    private $aReturn = [];

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<mode>[cm])/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    private function getChapterUrl($chapter = 1)
    {
        return 'http://www.' . self::DOMAIN . '/c/' . $this->aMatches['album'] . '/' . $chapter;
    }

    private function getChapter($sUrl)
    {
        $sUrl = str_replace('/c/', '/f/', $sUrl);
        foreach ($this->getDomParser()->load($sUrl)->find('img.img-responsive') as $oImg) {
            /** @var \DOMElement $oImg */
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR .
                str_pad(count($this->aReturn) + 1, 5, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $this->aReturn[$sBasename] = $sFilename;
        }
        return $this->aReturn;
    }

    private function getAllChapters()
    {
        $nbChapter = count($this->getDomParser()->loadFromUrl($this->sUrl, ['cleanupInput' => false])->find('.chlist li'));
        for ($chapter = 1; $chapter <= $nbChapter; $chapter++) {
            $this->getChapter($this->getChapterUrl($chapter));
        }
        return $this->aReturn;
    }

    public function getDownloadables()
    {
        return ($this->aMatches['mode'] == 'm')
            ? $this->getAllChapters()
            : $this->getChapter($this->sUrl);
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
