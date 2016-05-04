<?hh

namespace Pi\ServiceInterface\Types;

enum JobApplicationMethod : int {
	
	/**
	 * Email
	 */
	SubmitRemusesDirectly = 1;

	/**
	 * Mail or url
	 */
	OptionalApplication = 2;

}