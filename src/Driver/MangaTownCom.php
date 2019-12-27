<?php

namespace Yamete\Driver;

class MangaTownCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangatown.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aPages
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $sUrl = implode(
            '/',
            [
                'https:/',
                'www.' . self::DOMAIN,
                'manga',
                $this->aMatches['album'],
            ]
        ) . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        $oChapters = $this->getDomParser()
            ->load((string)$oRes->getBody(), ['cleanupInput' => false])
            ->find('ul.chapter_list a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        foreach ($aChapters as $oLink) {
            $sChapUrl = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sChapUrl);
            $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('.page_select select option');
            $aPages = iterator_to_array($oPages);
            $aPages = array_slice($aPages, 0, count($aPages) / 2);
            array_pop($aPages);
            foreach ($aPages as $oPageUrl) {
                $sPageUrl = 'https://www.' . self::DOMAIN . $oPageUrl->getAttribute('value');
                $oRes = $this->getClient()->request('GET', $sPageUrl);
                $oImg = $this->getDomParser()
                    ->load((string)$oRes->getBody())
                    ->find('#image')[0];
                $sFilename = $oImg->getAttribute('src');
                $iPos = strpos($sFilename, '?');
                $sFilename = substr($sFilename, 0, $iPos);
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(
            [
                'headers' => ['Cookie' => 'set=theme=1&h=1'],
            ]
        );
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
