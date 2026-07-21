<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Services\FeedbackService;

class FeedbackController extends Controller
{
    public function __construct(private FeedbackService $feedbackService)
    {
    }

    public function create()
    {
        $formData = $this->feedbackService->getFormData();

        return view('feedback.create', $formData);
    }

    public function store(StoreFeedbackRequest $request)
    {
        $this->feedbackService->create($request->validated());

        return redirect()->route('singlePage', $request->product_id)->with('success', 'Feedback created successfully.');
    }

    public function edit(Feedback $feedback)
    {
        $formData = $this->feedbackService->getFormData();

        return view('feedback.edit', array_merge($formData, ['feedback' => $feedback]));
    }

    public function update(StoreFeedbackRequest $request, Feedback $feedback)
    {
        $this->feedbackService->update($feedback, $request->validated());

        return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback)
    {
        $this->feedbackService->delete($feedback);

        return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
