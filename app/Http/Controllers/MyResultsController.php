<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Session;

use GuzzleHttp\Client;

use DB;



class MyResultsController extends Controller

{

	

	public function index(Request $request)

	{

		 $auth =   $request->session()->get('contactID');

		// dd($auth);

		if($auth){

			// constant variables

         $axl_apiurl = config('constants.AXL_Url');

         $AXL_api_token = config('constants.AXL_API_Token');

         $AXL_ws_token = config('constants.AXL_WS_Token');

         $apitoken = config('constants.api_key');

         $apiurl = config('constants.apiurl');

         // get session value
          $contact =   Session::get('contact_details');
          $member_id = $request->session()->get('member_id');
        // conenction to guzzle api
//dd($member_id);
        $client = new Client([

        'base_uri' => $axl_apiurl]); 

         

// $contact->CONTACTID; 
 $id = 2594099;
        $user = $client->request('GET', 'contact/enrolments/'.$id.'/',

         ['form_params' => ['type' => 'w', 'displayLength' =>10000],

      

       'headers' => ['apitoken' => $AXL_api_token,

       'wstoken' => $AXL_ws_token,]

        ]);
         $enrolments = json_decode($user->getBody());

          $eLearning = array();

        //if they have > 0 enrolments, check any e-learning now. We will assign this later to save API calls

        if (count($enrolments) > 0) {

//$contact->CONTACTID
// ranga's  contact id
      $id = 2594099;
        $get_eLearning = $client->request('GET', 'contact/enrolments/'.$id.'/',

        ['form_params' => ['type' => 'el', 'displayLength' =>10000],

      

       'headers' => ['apitoken' => $AXL_api_token,

       'wstoken' => $AXL_ws_token,]

        ]);
 $eLearning = json_decode($get_eLearning->getBody());

        }

  // conenction to guzzle api  sso
        $client = new Client([
        'base_uri' => $apiurl]); 
		
       $member_awards = $client->request('GET','members/'.$member_id.'/awards/',
         ['headers' => ['Authorization' => $apitoken, ]]);

       // $contactid = $contact->CONTACTID;
$get_surguard_transcript = json_decode($member_awards->getBody());

        $contactid = $id;
		
		
 return view('my-results', compact('enrolments','client','AXL_api_token','AXL_ws_token','contactid','eLearning','get_surguard_transcript'));

		}else{

			

		\Session::flash('message','You are not logged in. Please log in');

         return redirect('/login');

		}

	}



 // generate certificate

  public function getCertificate($id){



   $auth =   session()->get('contactID');

    if($auth){

      // constant variables

       

         $axl_apiurl = config('constants.AXL_Url');

         $AXL_api_token = config('constants.AXL_API_Token');

         $AXL_ws_token = config('constants.AXL_WS_Token');



              // conenction to guzzle api

        $client = new Client([

        'base_uri' => $axl_apiurl]); 

         

 try {

      $get_certificate = $client->request('GET', 'contact/enrolment/certificate',

        ['form_params' => ['enrolID' => $id],

      

       'headers' => ['apitoken' => $AXL_api_token,

       'wstoken' => $AXL_ws_token,]

        ]);

}

      // error response

catch (\Exception $ex) {

    // do nothing

   

    $response = $ex->getResponse();

   $responseBodyAsString = json_decode($response->getBody()->getContents(), true);

     //return $responseBodyAsString;
	 dd($responseBodyAsString['MESSAGES']);

     

} 



     

    $certificates = json_decode($get_certificate->getBody());

      $pdf = $certificates->CERTIFICATE;

     return view('pdf', compact('pdf')); 

       

  }else{

  \Session::flash('message','You are not logged in. Please log in');

         return redirect('/login'); 

  }

}

	

}	



