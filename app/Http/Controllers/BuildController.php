<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\Category;
use App\Services\BuilderService;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    public function __construct(private BuilderService $builderService)
    {
    }

    public function index()
    {
        $data = $this->builderService->getIndexData();

        return view('builder.index', $data);
    }

    public function getParts(Category $category)
    {
        $products = $this->builderService->getParts($category);

        return response()->json($products);
    }

    public function checkCompatibility(Request $request)
    {
        $request->validate([
            'part_ids'   => 'array',
            'part_ids.*' => 'integer|exists:products,id',
        ]);

        $warnings = $this->builderService->checkCompatibility($request->input('part_ids', []));

        return response()->json(['warnings' => $warnings]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'notes'      => 'nullable|string',
            'part_ids'   => 'required|array|min:1',
            'part_ids.*' => 'integer|exists:products,id',
        ]);

        $this->builderService->saveBuild(
            $request->input('name'),
            $request->input('notes'),
            $request->input('part_ids')
        );

        return response()->json(['success' => true, 'message' => 'Build saved successfully!']);
    }

    public function myBuilds()
    {
        $data = $this->builderService->getMyBuilds(auth()->id());

        return view('builder.my-builds', $data);
    }

    public function destroy(Build $build)
    {
        $this->authorize('delete', $build);

        $this->builderService->deleteBuild($build);

        return response()->json(['success' => true, 'message' => 'Build deleted successfully.']);
    }
}
