<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // List all contacts
    public function index(Request $request)
    {
        $contacts = Contact::query();

        if ($request->has('search')) {
            $contacts->where('name', 'like', '%' . $request->search . '%')
                     ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            $contacts->orderBy($request->sort, 'asc');
        } else {
            $contacts->orderBy('created_at', 'desc');
        }

        return view('contacts.index', ['contacts' => $contacts->paginate(10)]);
    }

    // Show form to create a new contact
    public function create()
    {
        return view('contacts.create');
    }

    // Store a new contact
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:contacts',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index');
    }

    // Show a specific contact
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return view('contacts.show', compact('contact'));
    }

    // Show form to edit a contact
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);

        return view('contacts.edit', compact('contact'));
    }

    // Update a contact
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index');
    }

    // Delete a contact
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        return redirect()->route('contacts.index');
    }
}
