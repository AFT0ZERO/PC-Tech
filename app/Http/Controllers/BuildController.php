<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckBuildCompatibilityRequest;
use App\Http\Requests\StoreBuildRequest;
use App\Models\Build;
use App\Models\Category;
use App\Services\BuilderService;
use App\Services\CatalogService;

class BuildController extends Controller
{
    public function __construct(
        private BuilderService $builderService,
        private CatalogService $catalogService,
    ) {
    }

    public function index()
    {
        $data = $this->builderService->getIndexData();
        $data['categories'] = $this->catalogService->getNavbarCategories();

        return view('builder.index', $data);
    }

    public function getParts(Category $category)
    {
        $products = $this->builderService->getParts($category);

        return response()->json($products);
    }

    public function partsPage(Category $category)
    {
        $data = $this->builderService->getPartsPageData($category);
        $data['categories'] = $this->catalogService->getNavbarCategories();

        return view('builder.parts', $data);
    }

    public function checkCompatibility(CheckBuildCompatibilityRequest $request)
    {
        $partIds = $request->input('part_ids', []);

        return response()->json([
            'warnings' => $this->builderService->checkCompatibility($partIds),
            'notes' => $this->builderService->missingSpecNotes($partIds),
        ]);
    }

    public function store(StoreBuildRequest $request)
    {
        $build = $this->builderService->saveBuild(
            $request->input('name'),
            $request->input('part_ids')
        );

        return response()->json([
            'success' => true,
            'message' => 'Build saved successfully!',
            'build_id' => $build->id,
            'warnings' => $this->builderService->compatibilityWarnings($build->fresh()),
        ]);
    }

    public function myBuilds()
    {
        $data = $this->builderService->getMyBuilds(auth()->id());
        $data['categories'] = $this->catalogService->getNavbarCategories();

        return view('builder.my-builds', $data);
    }

    public function destroy(Build $build)
    {
        $this->authorize('delete', $build);

        $this->builderService->deleteBuild($build);

        return response()->json(['success' => true, 'message' => 'Build deleted successfully.']);
    }
}
