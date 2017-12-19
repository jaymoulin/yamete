<?php

namespace Yamete;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class Command extends \Symfony\Component\Console\Command\Command
{
    const URL = 'url';
    const LIST_FILE = 'list';
    const DRIVERS = 'drivers';
    const PDF = 'pdf';

    protected function configure()
    {
        $this->setName('download')
            ->setDescription("Download a URL resources")
            ->addOption(self::URL, 'u', InputOption::VALUE_OPTIONAL, 'Url to download from')
            ->addOption(self::LIST_FILE, 'l', InputOption::VALUE_OPTIONAL, 'List file with multiple urls')
            ->addOption(self::PDF, 'p', InputOption::VALUE_NONE, 'Optional to create a PDF')
            ->addOption(
                self::DRIVERS,
                'd',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Optional array of drivers to add'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $aUrl = [];
        if (is_file($input->getOption(self::LIST_FILE))) {
            $aUrl = file($input->getOption(self::LIST_FILE));
        } elseif ($input->getOption(self::URL)) {
            $aUrl[] = $input->getOption(self::URL);
        } else {
            throw new \Exception('Required parameter : ' . implode(' or ', [self::URL, self::LIST_FILE]));
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
        foreach ($aUrl as $sUrl) {
            $sUrl = trim($sUrl);
            $output->writeln("<comment>Parsing $sUrl</comment>");
            $oResult = $oParser->parse($sUrl);
            $iNbUrl > 1 && $output->isVerbose() && $progress->advance();
            if (!$oResult) {
                $output->writeln("<error>Error while parsing $sUrl</error>");
                continue;
            }
            $this->download($oResult, $output);
            $output->writeln("<comment>Download $sUrl success!</comment>");
            !$input->getOption(self::PDF) ?: $this->pdf($oResult, $output);
        }
        if ($iNbUrl > 1 && $output->isVerbose()) {
            $progress->finish();
            $output->writeln('');
        }
    }

    private function pdf(ResultIterator $oResult, OutputInterface $output)
    {
        $output->writeln('<comment>Converting to PDF</comment>');
        $pdf = new PDF();
        $pdf->setMargins(0, 0);
        $pdf->createFromList($oResult);
        $baseName = null;
        foreach ($oResult as $sFileName => $sResource) {
            $baseName = dirname($sFileName);
            unlink($sFileName);
        }
        rmdir($baseName);
        $pdf->Output('F', $baseName . '.pdf');
        $output->writeln("<info>PDF created $baseName.pdf</info>");
    }

    private function download(ResultIterator $oResult, OutputInterface $output)
    {
        if ($output->isVerbose()) {
            $progress = new ProgressBar($output, count($oResult));
            $progress->start();
        }
        foreach ($oResult as $sFileName => $sResource) {
            $output->writeln(
                PHP_EOL . "<info>Downloading $sResource : $sFileName</info>",
                OutputInterface::VERBOSITY_VERY_VERBOSE
            );
            file_put_contents($sFileName, file_get_contents($sResource));
            $output->isVerbose() && $progress->advance();
        }
        if ($output->isVerbose()) {
            $progress->finish();
            $output->writeln('');
        }
    }
}
