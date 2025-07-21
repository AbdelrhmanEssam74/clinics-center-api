<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use function Pest\Laravel\json;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return response()->json($contacts);
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'subject' => 'required|string|min:10',
            'phone' => 'required|string|min:10',
            'message' => 'required|string|min:10',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'subject.required' => 'Subject is required',
            'message.required' => 'Message is required',
            'phone.required' => 'Phone number is required',
            'phone.min' => 'Phone number must be  10 numbers ',
            'message.min' => 'Message must be at least 10 characters long',
        ]);

        // Store the contact message in the database
        Contact::create($request->all());
        return response()->json(["massage" => 'Your message has been sent successfully!'], 201);
    }



    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully']);
    }
}
