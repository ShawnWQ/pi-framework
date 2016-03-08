<?hh

namespace SpotEvents\ServiceInterface;


use Pi\Service,
	Pi\Common\Http\HttpRequest,
	Pi\Common\Http\HttpMessage;

use SpotEvents\ServiceModel\OpenWeatherRequest,
	SpotEvents\ServiceModel\OpenWeatherResponse;


class OpenWeatherMapService extends Service {

	const WEATHER_TIMESTAMP = 'weather::timestamp';

	const WEATHER_BODY = 'weather::body';
	
	<<Request,Method('GET'),Route('/weather')>>
	public function get(OpenWeatherRequest $request)
	{
		$time = $this->cache()->get(self::WEATHER_TIMESTAMP);
		$timeDate = new \DateTime($time);
		$nowDate = new \DateTime('now');
		
		if(($nowDate->getTimestamp() - $timeDate->getTimestamp()) < 1000) {
			$body = $this->cache()->get(self::WEATHER_BODY);
			if(!is_null($body))
			return $this->convertResponseToDto($body);
		}

		$msg = $this->requestOpenWeatherApi();
		$dto = $this->convertResponseToDto($msg->getBody());
		$this->cache()->set(self::WEATHER_TIMESTAMP, $nowDate->getTimestamp());
		$this->cache()->set(self::WEATHER_BODY, $msg->getBody());
		return $dto;
	}

	protected function requestOpenWeatherApi() : HttpMessage
	{
		$req = new HttpRequest('http://api.openweathermap.org/data/2.5/weather?q=Viseu,pt&appid=097a9c80f805a700da6bb8387621f959');
		$msg = $req->send();
		return $msg;
	}

	public function convertResponseToDto(string $response) : OpenWeatherResponse
	{
		$fn = function($response){
			$obj = json_decode($response);
			$this->assertDtoValidation($obj);
			$response = new OpenWeatherResponse(
				$obj->weather[0]->main,
				$obj->weather[0]->description,
				$obj->main->temp,
				$obj->main->humidity,
				$obj->main->temp_min,
				$obj->main->temp_max,
				$obj->main->sea_level,
				$obj->main->grnd_level,
				$obj->wind->speed,
				$obj->sys->sunrise,
				$obj->sys->sunset
				);
			return $response;
		};

		try {
			return $fn($response);
		}
		catch(\Exception $ex) {
			return $fn($response); // second exception should be trowed
		}
		
	}

	protected function assertDtoValidation($obj)
	{
		return true;
	}
}