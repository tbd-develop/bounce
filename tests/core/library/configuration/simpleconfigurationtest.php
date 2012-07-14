<?php
require_once "PHPUnit/Autoload.php";
include_once( '../../../../core/library/autoloader.php');
require_once "../../../../core/library/configuration/iconfiguration.php";
require_once "../../../../core/library/configuration/simpleconfiguration.php";
include_once( '../../../../core/library/overrides.php');

class SimpleConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testCanLoadConfiguration()
    {
        $simple = SimpleConfiguration::Load("files/configuration.xml");

        $this->assertNotNull($simple);
    }

    public function testCanQueryDirectories() {
        $simple = SimpleConfiguration::Load("files/configurationwithdirectories.xml");

        $this->assertNotNull($simple->getConfiguration()->directories);
    }

    public function testCanSaveConfiguration() {
        if( file_exists("files/configurationsave.xml"))
            unlink( "files/configurationsave.xml");

        copy("files/configuration.xml", "files/configurationsave.xml");

        $simple = SimpleConfiguration::Load("files/configurationsave.xml");

        $node = $simple->getConfiguration()->addChild("test");
        $nodeToAdd = $node->addChild('add');
        $nodeToAdd->addAttribute("key", "test1");
        $nodeToAdd->addAttribute("value", "test2");

        $simple->Save();

        $loadConfiguration = SimpleConfiguration::Load("files/configurationsave.xml");

        $this->assertNotNull( $loadConfiguration->getConfiguration()->test);
    }
}
?>