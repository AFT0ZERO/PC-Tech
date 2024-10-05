<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contact_query = Contact::query();
        $search_param = $request->query('search');
        if (!empty($search_param)) {
            $contact_query = Contact::search($search_param);
        }
        $ContactFromDB = $contact_query->paginate(15);

        return view('admin.contact.index' , ['contacts' => $ContactFromDB]);
    }

    public function create()
    {
        return view('admin.contact.create');
    }

    public function store(Request $request)
    {
        request()->validate(
            [
                'name'=>['required','min:3'],
                'email'=>['required','email'],
                'mobile'=>['required','min:9','numeric'],
                'message'=>['required','min:10','string,max:5000'],
            ]
        );

        Contact::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'mobile'=>request('mobile'),
            'message'=>request('message'),
        ]);
        @dd('fix the success message and to_route');
        session()->flash('success', 'User Created Successfully!');
        return to_route('user.index');
    }

    public function show(Contact $contact)
    {
        return view("admin.contact.show", ["contact" => $contact]);
    }

    public function edit(Contact $contact)
    {

    }

    public function update(Request $request, Contact $contact)
    {

    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        session()->flash('success', 'Contact Deleted Successfully!');
        return to_route('contact.index');
    }
}
