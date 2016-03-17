<?php
namespace Test;

use Nette;
use Nette\Forms\Form;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

class ExtensionTest extends Tester\TestCase
{

    function setUp()
    {
    }

    function testRegistrationExtension()
    {
        Nette\Forms\NetteGMapPicker::register();

        $form = new Form;

        $mapControl = $form->addGMap('position', 'Position:')
            ->setWidth("500")
            ->setHeight("500");

        Assert::same('<div id="nettegmap" class="nettegmap-picker" data-map-attr=\'{"map":{"size":{"x":"500px","y":"500px"},"scrollwheel":false,"zoom":12}}\'><input type="text" name="position" id="nettegmap-search-box" placeholder="Vyhledávání"><div class="nettegmap-canvas"></div><input type="text" name="position[latitude]" id="latitude"><input type="text" name="position[longitude]" id="longitude"></div>', (string) $mapControl->getControl());
    }

}

$test = new ExtensionTest();
$test->run();