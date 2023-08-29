<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use App\Http\Resources\CompanyResource;
use Exception;
use Illuminate\Support\Facades\Log;


class ContactController extends Controller
{
    
    // get all contacts
    public function index()
    {
        try {
            $contacts = Contact::with('company')->get();
            return response([ 'data' => ContactResource::collection($contacts)], 200);
        }
        catch ( Exception $e ) {
            return response() -> json([
                "message" => "Could not fetch contacts" 
            ], 500 ); 
        }
        

    }

    // get a single contact
    public function show($id) {
        try {
            $contact = Contact::find($id);

            if (!$contact) {
                return response()->json([
                "message" => "Contact not found"
                ], 404);
            }


            $company = Company::find($contact->company_id);
            unset($contact['company_id']);

            if ($company) {
                $contact->company = $company;
            }

    
            return response(['data' => new ContactResource($contact)], 200);
        }
        catch ( Exception $e) {
            
            return response() -> json([
                "message" => "Error Getting Contact" 
            ], 500 ); 
        }

    }

    // edit a contact 
    public function update(Request $request, $id) {
        try {

            $requestData = $request->all();

        $firstname = $requestData['firstname'];
        $lastname = $requestData['lastname'];
        $company = $requestData['company'];
        $email = $requestData['email'];

        // Check if the company exists
        $companyData = Company::find($company["id"]); 
        if (!$companyData) {
            return response()->json([
                "message" => "Company not found"
            ], 404);
        }

        $contact = Contact::findOrFail($id);

        // Update the contact data
        $contact->firstname = $firstname;
        $contact->lastname = $lastname;
        $contact->email = $email;

        // Associate the contact with the company
        $contact->company_id = $company["id"] ;

        $contact->save();

        return response()->json([
            "message" => "Contact updated successfully" , 
            "contact" => $contact , 
            "id" => $company["id"]
        ], 200);
           

        }
        catch ( Exception $e ) {
            ;
            return response() -> json([
                "message" => "Error Updating Contact" ,
                "err" => $e -> getMessage()
            ], 500 ); 
        }
    }
}
