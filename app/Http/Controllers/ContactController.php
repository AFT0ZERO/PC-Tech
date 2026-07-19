<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function __construct(private ContactService $contactService)
    {
    }

    public function index(Request $request)
    {
        $search_param = $request->query('search');
        $ContactFromDB = $this->contactService->list($search_param);

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

        $this->contactService->create([
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
        $this->contactService->delete($contact);
        session()->flash('success', 'Contact Deleted Successfully!');
        return to_route('contact.index');
    }

    public function restore( $id)
    {
        $this->contactService->restore($id);
        session()->flash('success', 'Contact Restore Successfully!');
        return to_route('contact.showRestore');
    }

    public function showRestore( )
    {
        $contacts = $this->contactService->listTrashed();
        return view('admin.contact.restore' , ['contacts' => $contacts]);
    }
}
