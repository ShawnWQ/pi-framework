<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class RegistrationAvailabilityResponse extends Response {
	
	protected bool $available;

	public function setAvailable(bool $state)
	{
		$this->available = $state;
	}

    public function isAvailable()
    {
        return $this->available;
    }
}