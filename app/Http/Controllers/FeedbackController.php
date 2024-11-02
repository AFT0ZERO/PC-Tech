<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('feedback.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'rate' => 'required|integer|min:1|max:5',
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ]);

        Feedback::create($request->all());

        return redirect()->route('singlePage',$request->product_id)->with('success', 'Feedback created successfully.');
    }

    public function edit(Feedback $feedback)
    {
        $products = Product::all();
        $users = User::all();
        return view('feedback.edit', compact('feedback', 'products', 'users'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $request->validate([
            'message' => 'required|string',
            'rate' => 'required|integer|min:1|max:5',
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $feedback->update($request->all());

        return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
