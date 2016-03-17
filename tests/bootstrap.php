<?php

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/temp');

$configurator->addConfig(__DIR__ . '/config/config.neon');

return $configurator->createContainer();
