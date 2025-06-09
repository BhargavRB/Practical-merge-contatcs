<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index');
    }

    public function store(StoreContactRequest $request)
    {
        $response = [];
        try {
            $response = ContactService::store($request);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    
        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Contact deleted successfully']);
    }

    public function filter(Request $request)
    {
        $contacts = Contact::query()
        ->where('user_id', Auth::id())
        ->when($request->search, function($q) use ($request) {
            $search = $request->search;
            $q->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('gender', $search)
                      ->orWhere('custom_fields', 'like', "%{$search}%");
            });
        })
        ->get();    

        return view('contacts.contact_table', compact('contacts'));
    }
}
