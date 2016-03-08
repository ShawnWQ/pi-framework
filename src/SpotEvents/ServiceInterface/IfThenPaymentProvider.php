<?hh

namespace SpotEvents\ServiceInterface;

use SpotEvents\ServiceInterface\Interfaces\IPaymentProvider;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class IfThenPaymentProvider implements IPaymentProvider, IContainable {
	
	protected $wsEndpoint = 'https://www.ifthensoftware.com/IfmbWS/WsIfmb.asmx';


	public function validate($dto)
	{

	}

	public function ioc(IContainer $ioc)
	{

	}

	public function executePayment()
	{
		// create renf, validate in db
	}

	public static function createRef($ent, $subent, $seed, $total)
	{
		$subent=str_pad(intval($subent), 3, "0", STR_PAD_LEFT);
		$seed=str_pad(intval($seed), 4, "0", STR_PAD_LEFT);
		$chk_str=sprintf('%05u%03u%04u%08u', $ent, $subent, $seed, round($total*100));
		$chk_array=array(3, 30, 9, 90, 27, 76, 81, 34, 49, 5, 50, 15, 53, 45, 62, 38, 89, 17, 73, 51);
		$chk_val=0;
		for ($i = 0; $i < 20; $i++) {
			$chk_int = substr($chk_str, 19-$i, 1);
			$chk_val += ($chk_int%10)*$chk_array[$i];
		}
		$chk_val %= 97;
		$chk_digits = sprintf('%02u', 98-$chk_val);

		return $ref=$subent.$seed.$chk_digits;
	}
}