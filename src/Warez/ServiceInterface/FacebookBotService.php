<?hh

namespace Warez\ServiceInterface;

use Pi\Service;
use Pi\HostProvider;
use Warez\ServiceModel\FacebookBotLogin;
use Warez\ServiceModel\FacebookBotLoginResponse;
use Pi\Common\StringUtils;
use Pi\Common\Http\HttpRequest;
use Pi\Common\Http\HttpMessage;


class FacebookBotService extends Service {
	
	const facebookLoginUri = 'https://www.facebook.com/?_rdr=p';

	const facebookLoginPostUri = 'https://www.facebook.com/login.php?login_attempt=1&lwv=110';

	protected $hiddenElementsFixed = array('charset_test', 'li', 'm_ts');
	
	protected $hiddenElementsDynamic = array('lsd');


	<<Request>>
	public function login(FacebookBotLogin $request)
	{
		$response = new FacebookBotLoginResponse();
		$body = $this->loginRequest();
		$form = StringUtils::getStringBetween($body, '<form id="login_form"', '</form>');
		$regex='';
		$code = preg_match_all('#<input(.*?)/>#', $form, $matches);

		$loginReq = $this->getDefaultLoginRequestData($matches[0]);
		
		$loginReq['email'] = $request->getEmail();
		$loginReq['pass'] = $request->getPassword();
		$loginReq['version'] = 1;
		$loginReq['ajax'] = 0;

		$request = new HttpRequest(self::facebookLoginPostUri);
		$request->addPostFields($loginReq);
		$message = $request->send();

		return $response;
	}

	protected function getDefaultLoginRequestData(array $elements)
	{
		$req = array();
		foreach ($elements as $key => $element) {
			preg_match('#name="(.*?)"#', $element, $matches)[1];
			if(!is_array($matches) || !array_key_exists(1, $matches)) {
				continue;
			}

			$name = $matches[1];
			if(in_array($name, $this->hiddenElementsFixed) || in_array($name,  $this->hiddenElementsDynamic)) {
				preg_match('#value="(.*?)"#', $element, $matches)[1];
					if(!is_array($matches) && array_key_exists(1, $matches)) {
					continue;
				}
				$req[$name] = $matches[1];
			}
			
		}
		return $req;
	}

	protected function loginRequest() : string
	{
		$request = new HttpRequest(self::facebookLoginUri);
		return $request->send()->getBody();
	}
}