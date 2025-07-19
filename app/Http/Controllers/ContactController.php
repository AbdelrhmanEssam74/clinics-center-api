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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
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
