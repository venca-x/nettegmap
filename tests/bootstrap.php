<?php

declare(strict_types=1);
use Tester\Environment;

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


Environment::setup();
date_default_timezone_set('Europe/Prague');


/**
 * @param Closure|null $function
 * @return mixed|void|null
 */
function before(?Closure $function = null)
{
	static $val;
	if (!func_num_args()) {
		return $val ? $val() : null;
	}
	$val = $function;
}


function test(string $title, Closure $function): void
{
	before();
	$function();
}


/**
 * For tests that render map controls (NetteGMapViewer, NetteGMapLayer).
 */
function gmapTestTemplateFactory(): Nette\Bridges\ApplicationLatte\TemplateFactory
{
	$latteFactory = new class() implements Nette\Bridges\ApplicationLatte\LatteFactory
	{
		public function create(): Latte\Engine
		{
			return new Latte\Engine;
		}
	};

	return new Nette\Bridges\ApplicationLatte\TemplateFactory($latteFactory);
}
