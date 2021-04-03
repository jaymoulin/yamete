<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class MangairoCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'mangairo.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://m\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', 'https://m.' . self::DOMAIN . '/' . $this->aMatches['album']);
        $aReturn = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('#chapter_list a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.img_content') as $oImg) {
                /**
                 * @var AbstractNode $oImg
                 */
                $sFilename = $oImg->getAttribute('src');
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
