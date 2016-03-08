<?hh

namespace Pi\Validation;

class MessageFormater {

	/**
	 * Default Property Name placeholder.
	 */
	const propertyName = 'PropertyName';

	protected $placeholderValues = array();

	protected $additionalArgs = array();

	public function getAdditionalArguments()
	{
		return $this->additionalArgs;
	}

	public function appendArgument(string $name, $value) : MessageFormater
	{
		$this->placeholderValues[$name] = $value;
		return $this;
	}

	public function appendPropertyName(string $name)
	{
		return $this->appendArgument(self::propertyName, $name);
	}

	public function appendAdditionalArguments(array $additionalArgs)
	{
		$this->additionalArgs = $additionalArgs;
	}

	public function buildMessage(string $msgTemplate)
	{
		$result = $msgTemplate;

		foreach($this->placeholderValues as $key => $value) {
			$result = $this->replacePlaceholderWithValue($result, $key, $value);
		}
	}

	protected function shouldUseAditionalArguments()
	{
		return count($this->additionalArgs) > 0;
	}

	protected function replacePlaceholderWithValue(string $template, string $key, $value)
	{
		$placeholder = '{' . $key . '}';
		return str_replace($placeholder, $value === null ? null : (string)$value, $template);
	}
}