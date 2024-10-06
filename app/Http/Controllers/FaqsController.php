<?php

namespace App\Http\Controllers;

use App\Models\Faqs;
use Illuminate\Http\Request;

class FaqsController extends Controller
{

    public function index(Request $request)
    {
        $Faqs_query = Faqs::query();
        $search_param = $request->query('search');
        if (!empty($search_param)) {
            $Faqs_query = Faqs::search($search_param);
        }
        $FaqsFromDB = $Faqs_query->paginate(15);

        return view('admin.faq.index' , ['faqs' => $FaqsFromDB]);
    }


    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        request()->validate(
            [
               'question' => 'required',
                'answer' => 'required',
            ]
        );
        Faqs::create([
            'question' => request('question'),
            'answer' => request('answer')
        ]);
        session()->flash('success', 'FAQs Created Successfully!');
        return to_route('faq.index');
    }


    public function show(Faqs $faq)
    {
        return view("admin.faq.show", ["faq" => $faq]);
    }


    public function edit(Faqs $faq)
    {
        return view('admin.faq.edit', ['faq' => $faq]);
    }


    public function update(Request $request, Faqs $faq)
    {
        request()->validate(
            [
                'question' => 'required',
                'answer' => 'required',
            ]
        );
        $faq->update([
            'question' => request('question'),
            'answer' => request('answer')
        ]);
        session()->flash('success', 'FAQs Updated Successfully!');
        return to_route('faq.show', $faq->id);
    }


    public function destroy(Faqs $faq)
    {
        $faq->delete();
        session()->flash('success', 'FAQ Deleted Successfully!');
        return to_route('faq.index');
    }
    public function restore( $id)
    {
        $faq = Faqs::withTrashed()->find($id);
        $faq->restore();
        session()->flash('success', 'FAQ Restore Successfully!');
        return to_route('faq.showRestore');
    }

    public function showRestore( )
    {
        $faq = Faqs::onlyTrashed()->paginate(15);
        return view('admin.faq.restore' , ['faqs' => $faq]);
    }
}
