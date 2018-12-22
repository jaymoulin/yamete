<?php

namespace Yamete\Driver;

class HBrowse extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hbrowse.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/?]+)/?~',
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
        $oRes = $this->getClient()->request('GET', 'https://www.' . self::DOMAIN . '/' . $this->aMatches['album']);
        $aReturn = [];
        $i = 0;
        $sAccessor = '#main .listTable .listMiddle a';
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sAccessor) as $oLink) { //chapters
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sLink = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $oBody = $this->getDomParser()->load((string)$oRes->getBody());
            foreach ($oBody->find('#jsPageList a') as $oImg) { //images
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sHref = 'https://www.' . self::DOMAIN . $oImg->getAttribute('href') ?: $sLink . '/00001';
                $oRes = $this->getClient()->request('GET', $sHref);
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#mangaImage');
                $sFilename = $oImg->getAttribute('src');
                $sFilename = preg_match('~^https?://~', $sFilename)
                    ? $sFilename
                    : 'https://www.' . self::DOMAIN . $sFilename;
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 4, '0', STR_PAD_LEFT)
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
