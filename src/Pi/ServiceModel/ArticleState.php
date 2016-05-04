<?hh

namespace Pi\ServiceModel;

enum ArticleState : int {
	
	Draft = 1;
	Published = 2;
	Censored = 3;
	Removed = 99;
}