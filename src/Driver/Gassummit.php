<?php

namespace Yamete\Driver;

class Gassummit extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'gassummit.ru';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/[^/]+/(?<album>[^/]+)/$~',
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
        $sPageSelector = '#topn span';
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.readchap') as $oChapter) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oChapter
             * @var \PHPHtmlParser\Dom\AbstractNode $oSpan
             */
            $sUrl = trim($oChapter->getAttribute('href'));
            $oRes = $this->getClient()->request('GET', $sUrl);
            $oSpan = $this->getDomParser()->load((string)$oRes->getBody())->find($sPageSelector, 2);
            foreach ($oSpan->find('option') as $oLink) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oLink
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sUrl = str_replace('/read/1/', '/read/', $oLink->getAttribute('value'));
                $oRes = $this->getClient()->request('GET', $sUrl);
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#con img')[0];
                if (!$oImg) {
                    throw new \DomainException(
                        "Unable to parse $sUrl." . PHP_EOL . "Consider creating an issue on " .
                        "https://github.com/jaymoulin/yamete/issues/"
                    );
                }
                $sFilename = str_replace(' ', '%20', trim($oImg->getAttribute('src')));
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                    . str_pad($i++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $aReturn[$sPath] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
