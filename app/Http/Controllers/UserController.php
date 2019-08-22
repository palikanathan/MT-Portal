<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use GuzzleHttp\Client;
use DB;


class UserController extends Controller
{

   // public function __construct(Courses $courses)
   //  {
   //      $this->courses=$courses;
        
  
   //  }
	
  public function my_details()
  {
    $user = session()->get('contact_details');

    return view('my-details',compact('user'));
  }


     public function profile($id)
    {

             // get global varibles
         $axl_apiurl = config('constants.AXL_Url');
         $AXL_api_token = config('constants.AXL_API_Token');
         $AXL_ws_token = config('constants.AXL_WS_Token');



             // conenction to guzzle api
        $client = new Client([
        'base_uri' => $axl_apiurl]); 
         try {
 
       // ranga's contact id
         $id = 4772088;
       $contact = $client->request('GET', 'contact/'.$id,[
       'headers' => ['apitoken' => $AXL_api_token,
       'wstoken' => $AXL_ws_token,]
        ]);

$get_contact_details = json_decode($contact->getBody());

$response = array("status" => "success",  "contactDetails" => $get_contact_details);

}catch (\Exception $ex) {
        // encode the results 
   $response = $ex->getResponse();
     
     $responseBodyAsString = json_decode($response->getBody()->getContents(), true);
   $response = array("status" => "error", "message" => "An error occurred: $results->MESSAGES");
      
       
        }

        return json_encode($response);
    }



  public function update_mydetails(Request $request)
  {
	  

        // get global varibles
     $axl_apiurl = config('constants.AXL_Url');
         $AXL_api_token = config('constants.AXL_API_Token');
         $AXL_ws_token = config('constants.AXL_WS_Token');


    
	
       
        $update_details = array(
            "givenName" => $request->givenName,
            "surname" => $request->surname,
            "emailAddress" => $request->emailAddress,
            "title" => $request->title,
            "sex" => $request->sex,
            "dob" => $request->dob,
            "buildingName" => $request->buildingName,
            "unitNo" => $request->unitNo,
            "streetNo" => $request->streetNo,
            "streetName" => $request->streetName,
            "city" => $request->city,
            "state" => $request->state,
            "postcode" => $request->postcode,
            "POBox" => $request->POBox,
            "sbuildingName" => $request->sbuildingName,
            "sstreetNo" => $request->sstreetNo,
            "sstreetName" => $request->sstreetName,
            "scity" => $request->scity,
            "sstate" => $request->sstate,
            "spostcode" => $request->spostcode,
            "phone" => $request->phone,
            "workphone" => $request->workphone,
            "mobilephone" => $request->mobilephone,
            "CountryofBirthID" => $request->CountryofBirthID,
            "CountryofBirthID" => $request->CountryofBirthID,
            "IndigenousStatusID" => $request->IndigenousStatusID,
            "MainLanguageID" => $request->MainLanguageID,
            "MainLanguageID" => $request->MainLanguageID,
            "EnglishProficiencyID" => $request->EnglishProficiencyID,
            "DisabilityFlag" => $request->DisabilityFlag,
            "customField1" => $request->customField1,
            "USI" => $request->USI,
            "HighestSchoolLevelID" => $request->HighestSchoolLevelID,
            "HighestSchoolLevelYear" => $request->HighestSchoolLevelYear,
            "AtSchoolFlag" => $request->AtSchoolFlag,
            "LabourForceID" => $request->LabourForceID,
            "DisabilityTypeIDs" =>$request->DisabilityTypeIDs,
            "PriorEducationIDs" => $request->PriorEducationIDs,
            "contactID" => $request->contactID,
          );



             // conenction to guzzle api
        $client = new Client([
        'base_uri' => $axl_apiurl]); 
         try {

 
        $user = $client->request('PUT', 'contact/'.$request->contactID.'/',[
        'form_params' => $update_details,
       'headers' => ['apitoken' => $AXL_api_token,
       'wstoken' => $AXL_ws_token,]
        ]);
        $get_user_details = json_decode($user->getBody());
 $response = array("status" => "success", "message" => "You have successfully updated your details.");
}catch (\Exception $ex) {
        // encode the results 
   $response = $ex->getResponse();
     
     $responseBodyAsString = json_decode($response->getBody()->getContents(), true);
   $response = array("status" => "error", "message" => $responseBodyAsString->MESSAGES, "response" => $responseBodyAsString);
      
       
        }
        return json_encode($response);
}
	
}	
