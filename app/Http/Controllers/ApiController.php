<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
	/*
	* Handle Ping Request
	* Update Response Timestamp and return same body as Request
	*/
    public function pingRequest(Request $request)
    {
    	// Process request data and get response data and status code
    	$result = $this->processRequest($request);

    	// return xml response with appropriate data and status code
        return response($result['data'], $result['status_code'])
                  ->header('Content-Type', 'text/xml');
    }

    /*
	* Handle Reverse Request
	* Update Response Timestamp, and return body with reverse of string in Response
	*/
    public function reverseRequest(Request $request)
    {
    	// Process request data and get response data and status code
        $result = $this->processRequest($request);

    	// return xml response with appropriate data and status code
        return response($result['data'], $result['status_code'])
                  ->header('Content-Type', 'text/xml');
    }

    /*
    * Process http request and return response data and status code in array
    */
    private function processRequest($request) {
    	// Get body of http request
    	$bodyContent = $request->getContent();

		$errors = array();
		$data = array();
		try {
    		// convert body content from xml to php object
    		$xml = simplexml_load_string($bodyContent);

    		// check if xml conversion have errors
    		if ($xml === false) {
			    foreach(libxml_get_errors() as $error) {
			        $errors[] = $error->message;
			    }
			} else {
				// Convert Object to Array
			    $data = $this->convertXmlObjectToArray($xml);

			    // Pass php array data to get Response Data
			    // Check if correct header type is provided in http request body
			    if (isset($data['header']['type']) && ($data['header']['type'] == 'ping_request' || $data['header']['type'] == 'reverse_request')){
			    	$response_data = $this->getResponseData($data);
			    }else {
			    	$errors[] = "Invalid Request Type";
			    }
			}		
    	}catch(Exception $e) {
    		$errors[] = $e->getMessage();
    	}

    	if(empty($errors)){
    		// No errors 
    		$status_code = 200;
    	}else{
    		// Errors found
    		$status_code = 404;
    		$response_data = $this->getResponseData($data, $errors);
    	}

    	// Result containing status code and data for response
    	$result = array(
    		'status_code' => $status_code,
    		'data' => $response_data
     	);

    	// Returning result data
     	return $result;
    }

    /*
    * convert object to array
    */
    private function convertXmlObjectToArray($data) {
    	$json = json_encode($data);
		$result = json_decode($json,TRUE);
		return $result;
    }

    /*
    * Get XML response from request data in array and errors if any
    */
    private function getResponseData($data, $errors = array()){
    	// header content in xml
    	$header = '';
    	if(!empty($data)) {
	    	$header = '<header>';
	    	$data['header']['timestamp'] = date('c');
	    	$req_sender = $data['header']['sender'];
	    	$data['header']['sender'] = $data['header']['recipient'];
	    	$data['header']['recipient'] = $req_sender;

	    	if($data['header']['type'] == 'ping_request') {
	    		$data['header']['type'] = 'ping_response';
    		}else if($data['header']['type'] == 'reverse_request') {
	    		$data['header']['type'] = 'reverse_response';
    		}else {
    			$data['header']['type'] = 'nack';
    		}

	    	foreach ($data['header'] as $key => $value) {
	    		$header .= '<'.$key.'>'.$value.'</'.$key.'>';
	    	}
	    	$header .= '</header>';
	    }

    	// check if any errors 
    	if(empty($errors)){
    		// no error then
    		if($data['header']['type'] == 'ping_response') {
    			$body = '<body>';
		    	foreach ($data['body'] as $key => $value) {
		    		$body .= '<'.$key.'>'.$value.'</'.$key.'>';
		    	}
		    	$body .= '</body>';

    			$xml = '<?xml version="1.0" encoding="UTF-8"?>
						<ping_response>'.$header.$body.'</ping_response>';
    		}else if($data['header']['type'] == 'reverse_response') {
    			$body = '<body>';
		    	foreach ($data['body'] as $key => $value) {
		    		$body .= '<'.$key.'>'.$value.'</'.$key.'>';
		    		$body .= '<reverse>'.strrev($value).'</reverse>';
		    	}
		    	$body .= '</body>';

    			$xml = '<?xml version="1.0" encoding="UTF-8"?>
						<reverse_response>'.$header.$body.'</reverse_response>';
    		}
    	}else {
    		$error_message = '';
    		foreach ($errors as $key => $value) {
    			$error_message .= '<error>
								      <code>404</code>
								      <message>'.$value.'</message>
								    </error>';
    		}
    		$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<nack>'.$header.'<body>'.$error_message.'</body>
					</nack>';
    	}

    	return $xml;
    }

}
