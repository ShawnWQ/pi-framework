# Pi Validation Package

The validation is run agains DTO classes. Each DTO is validated by a self class extending the AbstractValidator class similar to FluendValidation.

The attribute **<<Validation>>** is required for each validation rule on a property, followed by the rules. Ie:

````
class TestRequest {

  <<Validation,NotEmpty,MaxLength(20)>>
  protected $retries;
}


class TestRequestValidator
  extends AbstractValidator<TestRequest> {

    public function __construct()
    {

    }
  }
````
