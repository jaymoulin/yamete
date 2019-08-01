<?php

namespace Yamete\Driver;

use \GuzzleCloudflare\Middleware;
use \GuzzleHttp\Cookie\FileCookieJar;

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
                'cookies' => new FileCookieJar(tempnam('/tmp', __CLASS__)),
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->push(Middleware::create());
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
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('center a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sLink = $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $sRegexp = '~<meta property="og:image" content="(?<file>[^"]+)"/>~';
            if (preg_match($sRegexp, (string)$oRes->getBody(), $aMatches)) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sLink = $aMatches['file'];
                $bHasHost = preg_match('~^https?://(?<domain>[^/]+)~', $sLink);
                $sFilename = $bHasHost ? $sLink : 'http://' . $sLink;
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
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
