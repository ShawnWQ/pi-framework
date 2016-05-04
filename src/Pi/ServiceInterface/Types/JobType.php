<?hh

namespace Pi\ServiceInterface\Types;


enum JobType : int {
	Unspecified = 1;
	Contract = 2;
	Permanent = 3;
}