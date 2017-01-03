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
            ->addOption(self::DRIVERS, 'd', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Optional array of drivers to add')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $aUrl = [];
        if ($input->hasOption(self::LIST_FILE) && is_file($input->getOption(self::LIST_FILE))) {
            $aUrl = file($input->getOption(self::LIST_FILE));
        } elseif ($input->hasOption(self::URL)) {
            $aUrl[] = $input->hasOption(self::URL);
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
            $output->writeln('Parsing ' . $sUrl);
            $oParser->parse($sUrl) || $output->writeln('Error while parsing ' . $sUrl);
        }
    }
}
