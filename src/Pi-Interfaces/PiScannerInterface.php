<?hh

namespace Pi\Interfaces;

/**
 * During build, scanners are used to get specific objects like services, filters, repositories
 * Implementation will be used to provide a way to refresh all metadata
 */
interface PiScannerInterface {
	
	public function getMeta() : Map;
}