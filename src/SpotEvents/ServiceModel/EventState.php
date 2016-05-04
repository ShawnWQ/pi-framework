<?hh

namespace SpotEvents\ServiceModel;

enum EventState : int {
	
	Draft = 1;
	Published = 2;
	Censored = 3;
	Removed = 99;
}