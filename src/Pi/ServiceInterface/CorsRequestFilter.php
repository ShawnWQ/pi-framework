<?hh

namespace Pi\ServiceInterface;
use Pi\Filters\PreRequestFilter;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Host\PhpResponse;
use Pi\Host\HostProvider;

class CorsRequestFilter extends PreRequestFilter {

	public function execute(IRequest $req, IResponse $res) : void
	{
	
			// Allow from any origin
		    if (isset($_SERVER['HTTP_ORIGIN'])) {
	
		        $res->headers()->add(Pair{"Access-Control-Allow-Origin", "{$_SERVER['HTTP_ORIGIN']}"});
		        $res->headers()->add(Pair{'Access-Control-Allow-Credentials', 'true'});
		        $res->headers()->add(Pair{'Access-Control-Max-Age', '86400'}); // cache for 1 day
		    }

		    // Access-Control headers are received during OPTIONS requests
		    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		        	$res->headers()->add(Pair{'Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE'});

		        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		        	$res->headers()->add(Pair{'Access-Control-Allow-Headers', "{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}"});

		        $res->endRequest(false);
		    }
	   
	}
}
