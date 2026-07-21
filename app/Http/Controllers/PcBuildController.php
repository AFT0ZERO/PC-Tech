<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\Product;
use App\Services\BuildItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Thin JSON API for managing the items of an already-persisted build.
 * All business rules (slot limits, compatibility) live in BuildItemService
 * and the compatibility engine — this controller only validates input,
 * authorizes, and shapes responses.
 */
class PcBuildController extends Controller
{
    public function __construct(
        private readonly BuildItemService $buildItemService,
    ) {
    }

    public function show(Build $build): JsonResponse
    {
        $this->authorize('view', $build);

        $build->load('items.product.category');

        return response()->json([
            'id' => $build->id,
            'name' => $build->name,
            'items' => $build->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'category' => $item->product->category->name ?? null,
                'quantity' => $item->quantity,
            ]),
            'compatibility' => $this->compatibilityPayload($build),
        ]);
    }

    public function addItem(Request $request, Build $build): JsonResponse
    {
        $this->authorize('update', $build);

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
        ]);

        $result = $this->buildItemService->addItem(
            $build,
            Product::findOrFail($data['product_id']),
            $data['quantity'] ?? 1,
        );

        return $this->resultResponse($result, 'Part added to the build.', 201);
    }

    public function updateItem(Request $request, Build $build, Product $product): JsonResponse
    {
        $this->authorize('update', $build);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $result = $this->buildItemService->updateQuantity($build, $product, $data['quantity']);

        return $this->resultResponse($result, 'Quantity updated.');
    }

    public function removeItem(Build $build, Product $product): JsonResponse
    {
        $this->authorize('update', $build);

        $result = $this->buildItemService->removeItem($build, $product);

        return $this->resultResponse($result, 'Part removed from the build.');
    }

    public function compatibility(Build $build): JsonResponse
    {
        $this->authorize('view', $build);

        return response()->json($this->compatibilityPayload($build));
    }

    private function resultResponse($result, string $successMessage, int $successStatus = 200): JsonResponse
    {
        if (! $result->added) {
            return response()->json(['message' => $result->blockedReason], 422);
        }

        return response()->json([
            'message' => $successMessage,
            'is_compatible' => $result->violations === [],
            'violations' => $this->formatViolations($result->violations),
        ], $successStatus);
    }

    private function compatibilityPayload(Build $build): array
    {
        $violations = $this->buildItemService->check($build);

        return [
            'is_compatible' => $violations === [],
            'violations' => $this->formatViolations($violations),
        ];
    }

    private function formatViolations(array $violations): array
    {
        return array_map(
            fn ($v) => ['type' => $v->ruleType, 'message' => $v->message],
            $violations,
        );
    }
}
