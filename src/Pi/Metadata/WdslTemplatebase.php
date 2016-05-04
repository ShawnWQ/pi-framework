<?hh

namespace Pi\Metadata;




abstract class WdslTemplatebase {
	
	protected string $xsd;

	protected string $serviceName;

	protected array<string> $replyOperationsNames;

	abstract function wsdlName() : string;

	/**
	 * Auxiliar to repeat templates
	 */
	public function repeatTemplate(string $template, array $args)
	{

	}

	protected string $replyOperationsTemplate = <<<EOF
	<wsdl:operation name="%s">
		<wsdl:input message="svc:%sIn" />
		<wsdl:output message="svc:%sOut" />
	</wsdl:peration>
EOF;
	
	protected string $oneWayOperationsTemplate = <<<EOF
	<wsdl:operation name="%s">
		<wsdl:input message="svc:%sIn" />
	</wsdl:operation>
EOF;

	protected function formatTemplate(
		string $wsdlName,
		string $xsd,
		string $replyMessages,
		string $oneWayMessages,
		string $replyOperations,
		string $oneWayOperations,
		string $wsdlServiceNamespace
	) : string
	{
		return <<<EOF
<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions name="$wsdlName"
	targetNamespace="$wsdlServiceNamespace"
	xmlns:svc="$wsdlServiceNamespace"
	xmlns:tns="$wsdlServiceNamespace"

	xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/"
	wmlns:soap="http://schemas.xmlsoap.org/wsdl/soap"
	xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12"
	xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" 
    xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" 
    xmlns:wsam="http://www.w3.org/2007/05/addressing/metadata" 
    xmlns:wsa="http://schemas.xmlsoap.org/ws/2004/08/addressing" 
    xmlns:wsp="http://schemas.xmlsoap.org/ws/2004/09/policy" 
    xmlns:wsap="http://schemas.xmlsoap.org/ws/2004/08/addressing/policy" 
    xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
    xmlns:msc="http://schemas.microsoft.com/ws/2005/12/wsdl/contract" 
    xmlns:wsaw="http://www.w3.org/2006/05/addressing/wsdl" 
    xmlns:wsa10="http://www.w3.org/2005/08/addressing" 
    xmlns:wsx="http://schemas.xmlsoap.org/ws/2004/09/mex">

    <wsdl:types>
    	$xsd
    </wsdl:types>

    $replyMessages
    $oneWayMessages
    $replyOperations
    $oneWayOperations

</wsdl:definitions>
EOF;

	public function __toString()
	{
		$replyServiceName = $this->serviceName ?: 'SyncReply';
		$replyOperations = $this->repeatTemplate($this->replyOperationsTemplate, $this->replyOperationsNames);
		$replyOperations = "<wsdl:portType name=\"I$replyServiceName\"" . $replyOperations . '</wsdl:portType>';
		$oneWayOperations = $this->repeatTemplate($this->oneWayOperationsTemplate, $this->oneWayOperations);
		
	}
}