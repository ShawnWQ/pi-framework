<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/21/15
 * Time: 3:04 AM
 */

namespace Pi;


class HttpError {


	static function notFound($message) {
		return new \Exception('nt found');
	}

	static function unauthorized($message) {
		return new \Exception('not authenticated');
	}
}