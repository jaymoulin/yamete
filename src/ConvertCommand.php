<?php

namespace Yamete;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure(): void
    {
        $this->setName('converter')
            ->setDescription("Convert downloaded urls to PDF");
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        ini_set('display_errors', $output->isDebug() ? '1' : '0');
        $output->writeln("<comment>Init conversion</comment>");
        $aList = [];
        $oIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getDirectory()));
        foreach ($oIterator as $oFilename) {
            /** @var \SplFileInfo $oFilename */
            if ($oFilename->isFile()) {
                $sFilename = $oFilename->getRealPath();
                $aList[dirname($sFilename)][] = $sFilename;
            }
        }
        foreach ($aList as $sDirName => $aFolder) {
            $this->pdf($aFolder, $output);
        }
    }

    /**
     * Path to download folder assets
     * @return string
     */
    private function getDirectory(): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'downloads']);
    }

    /**
     * @param array $aList Folder list / all assets path
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    private function pdf(array $aList, OutputInterface $output): void
    {
        $iMemoryLimit = ini_set('memory_limit', '2G'); //hack - this is NOT a solution. we better find something for PDF
        try {
            $output->writeln('<comment>Converting to PDF</comment>');
            $pdf = new PDF();
            $pdf->setMargins(0, 0);
            $pdf->createFromList(new \ArrayIterator(array_flip($aList)));
            $baseName = null;
            foreach ($aList as $sFileName) {
                $baseName = dirname($sFileName);
                unlink($sFileName);
            }
            rmdir($baseName);
            $pdf->Output('F', $baseName . '.pdf');
            $output->writeln("<comment>PDF created $baseName.pdf</comment>");
        } catch (\Exception $eException) {
            $sMessage = $eException->getMessage();
            $output->writeln("<error>PDF errored! : $sMessage</error>");
            ini_set('memory_limit', $iMemoryLimit);
            throw $eException;
        }
        ini_set('memory_limit', $iMemoryLimit);
    }
}
