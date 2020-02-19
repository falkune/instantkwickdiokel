<?php

include 'function.php';


$url = explode('/', $_GET['url']);
$service = $url[0];

switch($service){

	case 'Signup':
		echo signup($url);
		break;
	case 'Signin':
		echo signin($url);
		break;
	case 'Loged':
		echo loged($url);
		break;
	case 'ListPost':
		echo listpost($url);
		break;
	case 'SendTo':
		echo sendto($url);
		break;
	case 'Logout':
		echo logout($url);
		break;
	default:
		echo $error = json_encode([
         'status'  => 'failed',
         'message' => '404 not foud!'
      ]);
		break;

}
