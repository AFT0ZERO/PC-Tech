<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Services\FeedbackService;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'rate' => 'required|integer|min:1|max:5',
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $this->feedbackService->create($request->all());

        return redirect()->route('singlePage',$request->product_id)->with('success', 'Feedback created successfully.');
    }

    public function edit(Feedback $feedback)
    {
        $formData = $this->feedbackService->getFormData();
        return view('feedback.edit', array_merge($formData, ['feedback' => $feedback]));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $request->validate([
            'message' => 'required|string',
            'rate' => 'required|integer|min:1|max:5',
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $this->feedbackService->update($feedback, $request->all());

        return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback)
    {
        $this->feedbackService->delete($feedback);

        return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
