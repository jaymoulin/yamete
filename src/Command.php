<?php
namespace SiteDl;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends \Symfony\Component\Console\Command\Command
{
    const URL = 'url';
    const LIST_FILE = 'list';
    const DRIVERS = 'drivers';

    protected function configure()
    {
        $this->setName('download')
            ->setDescription("Download a URL resources")
            ->addOption(self::URL, 'u', InputOption::VALUE_OPTIONAL, 'Url to download from')
            ->addOption(self::LIST_FILE, 'l', InputOption::VALUE_OPTIONAL, 'List file with multiple urls')
            ->addOption(
                self::DRIVERS,
                'd',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Optional array of drivers to add'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $aUrl = [];
        if ($input->hasOption(self::LIST_FILE) && is_file($input->getOption(self::LIST_FILE))) {
            $aUrl = file($input->getOption(self::LIST_FILE));
        } elseif ($input->hasOption(self::URL)) {
            $aUrl[] = $input->getOption(self::URL);
        } else {
            throw new \Exception('Required parameter : ' . implode(' or ', [self::URL, self::LIST_FILE]));
        }
        $oParser = new Parser();
        if ($input->hasOption(self::DRIVERS)) {
            foreach ((array) $input->getOption(self::DRIVERS) as $sDriver) {
                $oParser->addDriverDirectory($sDriver);
            }
        }
        foreach ($aUrl as $sUrl) {
            $sUrl = trim($sUrl);
            $output->writeln('<comment>Parsing ' . $sUrl . '</comment>');
            $mResult = $oParser->parse($sUrl);
            $mResult
                ? $this->download($mResult, $output)
                : $output->writeln('<error>Error while parsing ' . $sUrl . '</error>');
        }
    }

    private function download(array $mResult, OutputInterface $output)
    {
        foreach ($mResult as $sFileName => $sResource) {
            $sFileName = is_numeric($sFileName) ? basename($sResource) : $sFileName;
            $sFileName = implode(DIRECTORY_SEPARATOR, [__DIR__, 'downloads', $sFileName]);
            if (!file_exists(dirname($sFileName))) {
                mkdir(dirname($sFileName), 0644, true);
            }
            $output->writeln('<info>Downloading ' . $sResource . ' : ' . $sFileName . '</info>');
            file_put_contents($sFileName, file_get_contents($sResource));
        }
    }
}
