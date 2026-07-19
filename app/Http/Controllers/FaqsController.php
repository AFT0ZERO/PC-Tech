<?php

namespace App\Http\Controllers;

use App\Models\Faqs;
use App\Services\FaqService;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    public function __construct(private FaqService $faqService)
    {
    }

    public function index(Request $request)
    {
        $search_param = $request->query('search');
        $FaqsFromDB = $this->faqService->list($search_param);

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
        $this->faqService->create([
            'question' => request('question'),
            'answer' => request('answer')
        ]);
        session()->flash('success', 'FAQs Created Successfully!');
        return back();
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
        $this->faqService->update($faq, [
            'question' => request('question'),
            'answer' => request('answer')
        ]);
        session()->flash('success', 'FAQs Updated Successfully!');
        return to_route('faq.show', $faq->id);
    }


    public function destroy(Faqs $faq)
    {
        $this->faqService->delete($faq);
        session()->flash('success', 'FAQ Deleted Successfully!');
        return back();
    }
    public function restore($id)
    {
        $this->faqService->restore($id);
        session()->flash('success', 'FAQ Restore Successfully!');
        return to_route('faq.showRestore');
    }

    public function showRestore()
    {
        $faqs = $this->faqService->listTrashed();
        return view('admin.faq.restore' , ['faqs' => $faqs]);
    }
}
