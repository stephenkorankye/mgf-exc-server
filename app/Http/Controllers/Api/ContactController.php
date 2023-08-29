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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            echo $e; 
            return response() -> json([
                "message" => "Error Getting Contact" 
            ], 500 ); 
        }

    }
}
