<?php

namespace Yamete\Driver;

use Tuna\CloudflareMiddleware;

class HentaiFromHell extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaifromhell.org';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/gallery2/(?<album>.+)\.html~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @param array $aOptions
     * @return \GuzzleHttp\Client
     */
    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        $oClient = parent::getClient(
            [
                'cookies' => new \GuzzleHttp\Cookie\FileCookieJar(tempnam('/tmp', __CLASS__)),
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->push(CloudflareMiddleware::create());
        return $oClient;
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
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('center a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sLink = $oLink->getAttribute('href');
            preg_match('~^https?://(?<domain>[^/]+)~', $sLink, $aDomains);
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.image-container img') as $oImg) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sLink = $oImg->getAttribute('src');
                $bHasHost = preg_match('~^https?://(?<domain>[^/]+)~', $sLink);
                $sFilename = $bHasHost ? $sLink : 'http://' . $sLink;
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sPath] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
