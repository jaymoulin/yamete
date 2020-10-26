<?php

namespace Yamete;

use DomainException;
use Exception;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class DownloadCommand extends \Symfony\Component\Console\Command\Command
{
    const URL = 'url';
    const LIST_FILE = 'list';
    const INTERACTIVE = 'interactive';
    const DRIVERS = 'drivers';
    const PDF = 'pdf';
    const ZIP = 'zip';
    const ERRORS = 'errors';

    protected function configure(): void
    {
        $this->setName('download')
            ->setDescription("Download an URL resources")
            ->addOption(self::URL, 'u', InputOption::VALUE_OPTIONAL, 'Url to download from')
            ->addOption(self::LIST_FILE, 'l', InputOption::VALUE_OPTIONAL, 'List file with multiple urls')
            ->addOption(
                self::INTERACTIVE,
                'i',
                InputOption::VALUE_NONE,
                'Interactive (send url to STDIN, never ends)'
            )
            ->addOption(self::PDF, 'p', InputOption::VALUE_NONE, 'Optional to create a PDF')
            ->addOption(self::ZIP, 'z', InputOption::VALUE_NONE, 'Optional to create a zip file')
            ->addOption(
                self::ERRORS,
                'e',
                InputOption::VALUE_OPTIONAL,
                'Optional file path to create artifacts error urls'
            )
            ->addOption(
                self::DRIVERS,
                'd',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Optional array of drivers to add'
            );
    }


    /**
     * @param $aUrls
     * @param bool $bIsInteractive
     * @return \Generator
     */
    private function getGenerator(array $aUrls = [], $bIsInteractive = false): \Generator
    {
        if (!$bIsInteractive) {
            foreach ($aUrls as $sUrl) {
                yield $sUrl;
            }
        } else {
            while ($sLine = fgets(STDIN)) {
                yield $sLine;
            }
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('display_errors', $output->isDebug() ? '1' : '0');
        $aUrl = [];
        if (!empty($input->getOption(self::ERRORS)) && !file_exists(dirname($input->getOption(self::ERRORS)))) {
            mkdir($input->getOption(self::ERRORS), 0777, true);
        }
        if (is_file($input->getOption(self::LIST_FILE))) {
            $aUrl = file($input->getOption(self::LIST_FILE));
        } elseif ($input->getOption(self::URL)) {
            $aUrl[] = $input->getOption(self::URL);
        } elseif ($input->getOption(self::INTERACTIVE)) {
            $output->writeln("<question>/!\ INTERACTIVE MODE /!\\\n\nSend ^C or EOF to end process</question>");
            $aUrl = [];
        } else {
            throw new Exception(
                'Required parameter : ' . implode(' or ', [self::URL, self::LIST_FILE, self::INTERACTIVE])
            );
        }
        $oParser = new Parser();
        if ($input->getOption(self::DRIVERS)) {
            foreach ((array)$input->getOption(self::DRIVERS) as $sDriver) {
                $oParser->addDriverDirectory($sDriver);
            }
        }
        $iNbUrl = count($aUrl);
        if ($iNbUrl > 1 && $output->isVerbose()) {
            $progress = new ProgressBar($output, $iNbUrl);
            $progress->start();
        }
        $fArtifact = empty($input->getOption(self::ERRORS)) ?: fopen($input->getOption(self::ERRORS), "a");
        foreach ($this->getGenerator($aUrl, $input->getOption(self::INTERACTIVE)) as $sUrl) {
            try {
                $sUrl = trim($sUrl);
                $output->writeln("<comment>Parsing $sUrl</comment>");
                $oResult = $oParser->parse($sUrl);
                $iNbUrl > 1 && $output->isVerbose() && $progress->advance();
                if (!$oResult) {
                    throw new DomainException(
                        "Unable to parse $sUrl." . PHP_EOL . "Consider creating an issue on " .
                        "https://github.com/jaymoulin/yamete/issues/"
                    );
                }
                $this->download($oResult, $output);
                $output->writeln("<info>Download $sUrl success!</info>");
                !($input->getOption(self::ZIP) && !$input->getOption(self::PDF)) ?: $this->zip($oResult, $output);
                !$input->getOption(self::PDF) ?: $this->pdf($oResult, $output);
            } catch (Exception $eException) {
                is_resource($fArtifact) && fputs($fArtifact, $sUrl . PHP_EOL);
                if ($iNbUrl == 1) {
                    throw $eException;
                }
                $sMessage = $eException->getMessage();
                $output->writeln("<error>$sMessage</error>");
                continue;
            }
        }
        if ($iNbUrl > 1 && $output->isVerbose()) {
            $progress->finish();
            $output->writeln('');
        }
        return 0;
    }

    private function zip(ResultIterator $oResult, OutputInterface $output): void
    {
        $output->writeln('<comment>Creating zip archive</comment>');
        $zip = new ZipArchive();
        $isOpened = false;
        $baseName = null;
        foreach ($oResult as $sFileName => $sResource) {
            $baseName = dirname($sFileName);
            if (!$isOpened) {
                $isOpened = true;
                $zip->open($baseName . '.zip', ZipArchive::CREATE);
            }
            $zip->addFile($sFileName);
        }
        $zip->close();
        foreach ($oResult as $sFileName => $sResource) {
            unlink($sFileName);
        }
        rmdir($baseName);
        $output->writeln("<comment>Zip created $baseName.zip</comment>");
    }

    /**
     * @param ResultIterator $oResult
     * @param OutputInterface $output
     * @throws \Exception
     */
    private function pdf(ResultIterator $oResult, OutputInterface $output): void
    {
        $iMemoryLimit = ini_set('memory_limit', '2G'); //hack - this is NOT a solution. we better find something for PDF
        try {
            $output->writeln('<comment>Converting to PDF</comment>');
            $pdf = new PDF();
            $pdf->setMargins(0, 0);
            $pdf->createFromList($oResult);
            $baseName = null;
            foreach ($oResult as $sFileName => $sResource) {
                $baseName = dirname($sFileName);
                $bDone = false;
                foreach (['.jpg', '.jpeg', '.png', '.gif', '.webp'] as $sSource) {
                    foreach (['.png', '.gif', '.jpg', '.jpeg'] as $sDest) {
                        $bDone = @unlink($sFileName);
                        if (!$bDone) {
                            $bDone = @unlink(str_replace($sSource, $sDest, $sFileName));
                        }
                        if ($bDone) {
                            break;
                        }
                    }
                    if ($bDone) {
                        break;
                    }
                }
            }
            rmdir($baseName);
            $pdf->Output('F', $baseName . '.pdf');
            $output->writeln("<comment>PDF created $baseName.pdf</comment>");
        } catch (Exception $eException) {
            $sMessage = $eException->getMessage();
            $output->writeln("<error>PDF errored! : $sMessage</error>");
            ini_set('memory_limit', $iMemoryLimit);
            throw $eException;
        }
        ini_set('memory_limit', $iMemoryLimit);
    }

    /**
     * @param ResultIterator $oResult
     * @param OutputInterface $output
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    private function download(ResultIterator $oResult, OutputInterface $output): void
    {
        /** @var Downloadable[] $oResult */
        $progress = null;
        if ($output->isVerbose()) {
            $progress = new ProgressBar($output, count($oResult));
            $progress->start();
        }
        $bSuccess = null;
        foreach ($oResult as $sFileName => $oResource) {
            $bSuccess = is_null($bSuccess) ? true : $bSuccess;
            $sPath = $oResource->getUrl();
            $output->writeln(
                PHP_EOL . "<question>Downloading $sPath > $sFileName</question>",
                OutputInterface::VERBOSITY_VERY_VERBOSE
            );
            if ($oResource->download()->getStatusCode() != 200) {
                $bSuccess = false;
                $output->writeln("<error>Download error : $sPath</error>");
            }
            $output->isVerbose() && $progress->advance();
        }
        if (!$bSuccess) {
            throw new Exception(
                "No result on download url" . PHP_EOL .
                "consider creating an issue https://github.com/jaymoulin/yamete/issues/"
            );
        }
        if ($output->isVerbose()) {
            $progress->finish();
            $output->writeln('');
        }
    }
}
