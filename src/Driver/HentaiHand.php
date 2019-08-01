<?php

namespace Yamete\Driver;

class HentaiHand extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaihand.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comic/(?<album>[0-9]+)/?$~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        $iNbPages = count($this->getDomParser()->load((string)$oRes->getBody())->find('a.gallerythumb'));
        for ($iPage = 1; $iPage <= $iNbPages; $iPage++) {
            $oRes = $this->getClient()
                ->request('GET', 'https://' . implode('/', [self::DOMAIN, $this->aMatches['album'], $iPage]));
            $sRule = '#image-container .item img';
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sRule) as $oImg) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
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
