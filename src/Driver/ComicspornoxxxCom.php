<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class ComicspornoxxxCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'comicspornoxxx.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(?<domain>' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.thumbnail a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sLink = $oLink->getAttribute('href');
            $aUrlInfo = parse_url($sLink);
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#center .text-center img') as $oImg) {
                /**
                 * @var AbstractNode $oImg
                 */
                $sFilename = $aUrlInfo['scheme'] . '://' . $aUrlInfo['host'] . '/' . $oImg->getAttribute('src');
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        $oClient = parent::getClient(
            [
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
        /**
         * @var HandlerStack $oHandler
         */
        return $oClient;
    }
}
