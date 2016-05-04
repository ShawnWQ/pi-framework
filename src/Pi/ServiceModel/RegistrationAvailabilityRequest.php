<?hh

namespace Pi\ServiceModel;

class RegistrationAvailabilityRequest {
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
	
	protected $email;

}