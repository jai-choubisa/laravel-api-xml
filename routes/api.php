<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API for voiceworks assessment
|
*/

/*
* The client sends a ping_request message with an optional echo element in the body. 
* In response to this request, the ping service answers with a ping_response message, 
* optionally copying the echo element in the body.
*/
Route::post('ping_request','ApiController@pingRequest');

/*
* The reverse function reverses a given string. 
* The client sends a reverse_request message with a string element in the body. 
* In the reverse_response, both the original string and the reversed string are returned.
*/
Route::post('reverse_request','ApiController@reverseRequest');