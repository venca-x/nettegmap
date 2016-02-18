<?php

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../temp');
//$configurator->createRobotLoader()
//	->addDirectory(__DIR__ . '/../src')
	//->register();

return $configurator->createContainer();
