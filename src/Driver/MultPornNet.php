<?php

namespace Yamete\Driver;

class MultPornNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'multporn.net';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/comics/(?<album>[^/]+)/?$~',
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
        $sBody = (string)$oRes->getBody();
        $aReturn = [];
        $i = 0;
        $sRegExp = '~{"configUrl":"([^"]+)"~';
        if (!preg_match($sRegExp, $sBody, $aMatch)) {
            return [];
        }
        $sUrl = 'https://' . $this->getDomain() . str_replace('\/', '/', $aMatch[1]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('image') as $oImg) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sFilename = preg_replace('~\?.*$~', '', $oImg->getAttribute('largeimageurl'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
