<?hh

namespace Pi\ServiceInterface;

class NginxProvider {

	public function __construct(protected string $rootPath)
	{

	}

	public function create(string $domainName, \MongoId $appId)
	{
		$rootPath = $this->rootPath;
		$code = '';
		$code .= sprintf(<<<EOF
	       	server {
		        listen 80;
		        server_name $domainName www.$domainName;
		        root $rootPath/$domainName;
		        index index.php;

		        location / {
		            try_files $uri $uri/ /index.php?$query_string;
		        }

		        location ~ \.(hh|php)$ {
		            fastcgi_keep_conn on;
		            fastcgi_pass   127.0.0.1:9000;
		            fastcgi_index  index.php;
		            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
		            include        fastcgi_params;
		        }
	    	}

EOF
          );
	}
}