<?php
use Mocks\BibleHost;
use Pi\MessagePack\MessagePackService;

class MessagePackPluginTest extends \PHPUnit_Framework_TestCase {

	public function testPluginRegisterTheServiceSerializer()
	{
		$host = new BibleHost();
		$host->init();

		$instance = $host->container()->get('IServiceSerializer');

		$this->assertTrue($instance instanceof MessagePackService);
	}
}
