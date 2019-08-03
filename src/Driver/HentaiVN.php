<?php

namespace Yamete\Driver;

use \GuzzleCloudflare\Middleware;
use \GuzzleHttp\Cookie\FileCookieJar;

class HentaiVN extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaivn.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^.]+).html$~',
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
        $index = 0;
        $bIsNoChapterFormat = (bool)preg_match('~[0-9]+-[0-9]+-.+~', $this->aMatches['album']);
        $oList = $bIsNoChapterFormat
            ? [$this->sUrl]
            : $this->getDomParser()->load((string)$oRes->getBody())->find('.listing td a');
        $iChapters = count($oList);
        foreach ($oList as $oLink) { //chapters
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sLink = $bIsNoChapterFormat ? $oLink : 'http://' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $oBody = $this->getDomParser()->load((string)$oRes->getBody());
            foreach ($oBody->find('#image img') as $oImg) { //images
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $iPos = strpos($oImg->getAttribute('src'), '?');
                $sFilename = substr($oImg->getAttribute('src'), 0, $iPos ? $iPos : strlen($oImg->getAttribute('src')));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . $iChapters . DIRECTORY_SEPARATOR
                    . str_pad($index++, 5, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $aReturn[$sBasename] = $oImg->getAttribute('src');
            }
            --$iChapters;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
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
        $oHandler->remove('cloudflare');
        $oHandler->push(Middleware::create(), 'cloudflare');
        return $oClient;
    }
}
