<?hh

namespace Pi\Host\Handlers;




abstract class SoapHandler extends AbstractPiHandler {
	
	abstract public function wsdlName() : string;
}