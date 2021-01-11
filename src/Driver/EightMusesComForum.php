<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class EightMusesComForum extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = '8muses.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://comics.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/forum/discussion/(?<categ>[^/]+)/(?<album>[^/]+)/?~',
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
        $aReturn = [];
        $this->sUrl = 'https://comics.' . self::DOMAIN . '/forum/discussion/' . $this->aMatches['categ'] . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.bbImage') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sFilename = strpos($oLink->getAttribute('src'), 'http') !== false
                ? $oLink->getAttribute('src')
                : 'https://comics.' . self::DOMAIN . $oLink->getAttribute('src');
            if (!$sFilename) {
                continue;
            }
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
