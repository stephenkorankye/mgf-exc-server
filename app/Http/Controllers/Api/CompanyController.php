<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Company;
use App\Http\Resources\ContactResource;
use App\Http\Resources\CompanyResource;
use Exception;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    // get all companies 
    public function index()
    {
        try {
            $companies = Company::all() ; 
            $contacts = Contact::all(); 

            $theCompanies = $companies->map(function ($company) use ($contacts) {
                $count = 0;
            
                foreach ($contacts as $contact) {
                    if ($contact->company->id == $company->id) {
                        $count++;
                    }
                }
            
                return [
                    "id" => $company->id,
                    "name" => $company->name,
                    "postcode" => $company->postcode,
                    "count" => $count
                ];
            });

            return response([ 'data' => ContactResource::collection($theCompanies)], 200);

        }
        catch ( Exception $e ) {
            return response() -> json([
                "message" => "Could not fetch companies" , 
                "err" => $e -> getMessage() 
            ], 500 ); 
        }
        

    }
}
