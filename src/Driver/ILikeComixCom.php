<?php

namespace Yamete\Driver;

class ILikeComixCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'ilikecomix.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/porncomix/(?<album>[^/]+)~',
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
         * @var \Traversable $oPages
         * @var \PHPHtmlParser\Dom\AbstractNode $oImgs
         */
        $sUrl = 'https://' . self::DOMAIN . '/porncomix/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('figure > a');
        $index = 0;
        $aReturn = [];
        foreach ($oPages as $oLink) {
            $sFilename = $oLink->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
