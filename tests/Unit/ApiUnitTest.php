<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class ApiUnitTest extends TestCase
{
 	/*
 	* Unit Testing Ping Request API
 	*/
    public function test_can_ping_request() {
    	// Using Faker for generating dummy data
    	$faker = Faker::create();

    	$request_sender = $faker->company;
    	$request_recipient = $faker->name;
    	$request_uid = $faker->uuid;
    	$request_message = $faker->text;
        $data = '<?xml version="1.0" encoding="UTF-8"?>
				<ping_request>
				  <header>
				    <type>ping_request</type>
				    <sender>'.$request_sender.'</sender>
				    <recipient>'.$request_recipient.'</recipient>
				    <reference>ping_request_'.$request_uid.'</reference>
				    <timestamp>'.date('c').'</timestamp>
				  </header>
				  <body>
				    <echo>'.$request_message.'</echo>
				  </body>
				</ping_request>';
        
        $response = $this->call('POST', '/api/ping_request', [], [], [], [], $data);

        // Testing Response Status Code
        $this->assertEquals(200, $response->getStatusCode());

        $type = '';
        $sender = '';
        $recipient = '';
        $reference = '';
        $body_echo = '';
        try {
    		// convert body content from xml to php object
    		$xml = simplexml_load_string($response->getContent());
    		// check if xml conversion have no errors
    		if ($xml !== false) {
			    $json_data = json_encode($xml);
				$result = json_decode($json_data,TRUE);
				// dd($result);
				$type = $result['header']['type'];
		        $sender = trim($result['header']['sender']);
		        $recipient = trim($result['header']['recipient']);
		        $reference = $result['header']['reference'];
		        $body_echo = $result['body']['echo'];
			}		
    	}catch(Exception $e) {
    		//error in converting xml response to php array
    	}

    	// Testing Response data asserting to right value
        $this->assertEquals('ping_response', $type);
        $this->assertEquals($request_recipient, $sender);
        $this->assertEquals($request_sender, $recipient);
        $this->assertEquals('ping_request_'.$request_uid, $reference);
        $this->assertEquals($request_message, $body_echo);

    }
    
    /*
 	* Unit Testing Reverse Request API
 	*/
    public function test_can_reverse_request() {
    	// Using Faker for generating dummy data
    	$faker = Faker::create();

    	$request_sender = $faker->company;
    	$request_recipient = $faker->name;
    	$request_uid = $faker->uuid;
    	$request_message = $faker->text;

        $data = '<?xml version="1.0" encoding="UTF-8"?>
				<reverse_request>
				  <header>
				    <type>reverse_request</type>
				    <sender>'.$request_sender.'</sender>
				    <recipient>'.$request_recipient.'</recipient>
				    <reference>reverse_request_'.$request_uid.'</reference>
				    <timestamp>'.date('c').'</timestamp>
				  </header>
				  <body>
				    <string>'.$request_message.'</string>
				  </body>
				</reverse_request>';
        
        $response = $this->call('POST', '/api/reverse_request', [], [], [], [], $data);
        
        // Testing Response Status Code
        $this->assertEquals(200, $response->getStatusCode());

        $type = '';
        $sender = '';
        $recipient = '';
        $reference = '';
        $body_message = '';
        $body_reverse_message = '';
        try {
    		// convert body content from xml to php object
    		$xml = simplexml_load_string($response->getContent());
    		// check if xml conversion have no errors
    		if ($xml !== false) {
			    $json_data = json_encode($xml);
				$result = json_decode($json_data,TRUE);
				// dd($result);
				$type = $result['header']['type'];
		        $sender = trim($result['header']['sender']);
		        $recipient = trim($result['header']['recipient']);
		        $reference = $result['header']['reference'];
		        $body_message = $result['body']['string'];
		        $body_reverse_message = $result['body']['reverse'];
			}		
    	}catch(Exception $e) {
    		//error in converting xml response to php array
    	}

    	// Testing Response data asserting to right value
        $this->assertEquals('reverse_response', $type);
        $this->assertEquals($request_recipient, $sender);
        $this->assertEquals($request_sender, $recipient);
        $this->assertEquals('reverse_request_'.$request_uid, $reference);
        $this->assertEquals($request_message, $body_message);
        $this->assertEquals(strrev($request_message), $body_reverse_message);
    }
}
