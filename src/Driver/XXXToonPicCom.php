<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class XXXToonPicCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'xxxtoonpic.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) .
            ')/(?<category>[^/?]+)/(?<album>[^/?]+)/?~',
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
        $oRes = $this->getClient()->request(
            'GET',
            implode('/', ['https://www.' . self::DOMAIN, $this->aMatches['category'], $this->aMatches['album']])
        );
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gallery-thumb a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sUrl = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sUrl);
            $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#image-container img')[0];
            $sFilename = $oImg->getAttribute('src');
            $sFilename = strpos($sFilename, 'http') !== false ? $sFilename : 'https:' . $sFilename;
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

}
