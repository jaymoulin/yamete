<?php

namespace Yamete\Driver;

class DuppNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'd-upp.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) . ')/g/(?<album>[0-9]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.img-url') as $oImg) {
            foreach (['a', 'b'] as $cCdn) {
                $sFilename = str_replace('ja.', $cCdn . 'a.', 'https:' . $oImg->text);
                $oRes = $this->getClient()->request('GET', $sFilename, ["http_errors" => false]);
                if ($oRes->getStatusCode() != 200) {
                    continue;
                }

                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename(preg_replace('~\?(.*)$~', '', $sFilename));
                $aReturn[$sBasename] = $sFilename;
                break;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
