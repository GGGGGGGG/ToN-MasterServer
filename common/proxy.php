$masterserver = 'masterserver.savage2.s2games.com';

function auth_proxy($email, $password) {
	$r = new HttpRequest('http://'.$masterserver, HttpRequest::METH_POST);
	$r->addPostFields(array('f' => 'auth', 'email' => $email, 'password' => $password));
	try {
		$data = $r->send()->getBody();
		return $data;
	} catch (HttpException $ex) {
		//echo $ex;
	}
	return array('error' => 'Error in receiving response from official masterserver :/');
}

function get_online_proxy() {
	$r = new HttpRequest('http://'.$masterserver, HttpRequest::METH_POST);
	$r->addPostFields(array('f' => 'get_online');
	try {
		$data = $r->send()->getBody();
		return $data;
	} catch (HttpException $ex) {
		//echo $ex;
	}
	return array();	
}
