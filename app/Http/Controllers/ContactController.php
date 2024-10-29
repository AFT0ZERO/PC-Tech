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



    public function store(Request $request)
    {
        request()->validate(
            [
                'name'=>['required','min:3'],
                'email'=>['required','email'],
                'mobile'=>['required','min:9','numeric'],
                'message'=>['required','min:10','max:5000'],
            ]
        );

        Contact::create([
            'user_id'=>request('user_id'),
            'name'=>request('name'),
            'email'=>request('email'),
            'mobile'=>request('mobile'),
            'message'=>request('message'),
        ]);

        session()->flash('success', 'Your message has been sent successfully!');
        return to_route('contact');
    }

    public function show(Contact $contact)
    {
        return view("admin.contact.show", ["contact" => $contact]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        session()->flash('success', 'Contact Deleted Successfully!');
        return to_route('contact.index');
    }

    public function restore( $id)
    {
        $Contact = Contact::withTrashed()->find($id);
        $Contact->restore();
        session()->flash('success', 'Contact Restore Successfully!');
        return to_route('contact.showRestore');
    }

    public function showRestore( )
    {
        $contact = Contact::onlyTrashed()->paginate(15);
        return view('admin.contact.restore' , ['contacts' => $contact]);
    }
}
