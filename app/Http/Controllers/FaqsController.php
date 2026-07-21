<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaqRequest;
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

        return view('admin.faq.index', ['faqs' => $FaqsFromDB]);
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(StoreFaqRequest $request)
    {
        $this->faqService->create($request->validated());
        session()->flash('success', 'FAQs Created Successfully!');

        return back();
    }

    public function show(Faqs $faq)
    {
        return view('admin.faq.show', ['faq' => $faq]);
    }

    public function edit(Faqs $faq)
    {
        return view('admin.faq.edit', ['faq' => $faq]);
    }

    public function update(StoreFaqRequest $request, Faqs $faq)
    {
        $this->faqService->update($faq, $request->validated());
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

        return view('admin.faq.restore', ['faqs' => $faqs]);
    }
}
