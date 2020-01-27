<?php

namespace Yamete\Driver;

class BestPornComixCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'bestporncomix.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/gallery/(?<album>[^/]+)~',
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
        $sUrl = 'https://' . self::DOMAIN . '/gallery/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $oPageList = $this->getDomParser()->load((string)$oRes->getBody())->find('.gallery-item a');
        $index = 0;
        foreach ($oPageList as $oHref) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oHref
             * @var \PHPHtmlParser\Dom\AbstractNode $oImage
             */
            $oRes = $this->getClient()->request('GET', $oHref->getAttribute('href'));
            $oImage = $this->getDomParser()->load((string)$oRes->getBody())->find('.attachment-image a')[0];
            $sFilename = $oImage->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
