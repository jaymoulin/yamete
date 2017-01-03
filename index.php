#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';

$oCommand = new SiteDl\Command;

$oApplication = new \Symfony\Component\Console\Application;
$oApplication->add($oCommand);
$oApplication->setDefaultCommand($oCommand->getName(), true);
$oApplication->run();