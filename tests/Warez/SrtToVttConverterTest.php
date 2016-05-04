<?hh

class SrtToVttConverter {

	public function convertFromFile(string $path)
	{
		$file = fopen($path, 'r');
	}
}
class SrtToVttConverterTest extends \PHPUnit_Framework_TestCase {

	public function testCanConvert()
	{
		$file = <<<EOT
		1
		00:01:21,700 --> 00:01:24,675
		Life on the road is something
		I was raised to embrace.		
EOT;
		
		
		$vtt = <<<EOT
		WEBVTT
		01:21.700 --> 01:24.675
		Life on the road is something
		I was <i>raised</i> to embrace. 
EOT;

	}
}