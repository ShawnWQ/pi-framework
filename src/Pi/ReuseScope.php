<?hh

namespace Pi;




enum ReuseScope : int {
	/**
	 * Singleton scope (a instance is used per application lifetime)
	 */
	Container = 0;
	/**
	 * Request scope (a instance is used per request lifetime)
	 */
	Request = 1;
	/**
	 * Transient scope (a new instance is created everytime)
	 */
	None = 2;
}
