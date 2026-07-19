<?php

namespace App\Services;

use App\Models\Store;
use App\Repositories\StoreRepository;
use Illuminate\Http\UploadedFile;

class StoreService
{
    public function __construct(
        private StoreRepository $storeRepository,
        private ImageUploadService $imageUploadService
    ) {
    }

    public function list(?string $search = null, string $sort = 'name_asc')
    {
        return $this->storeRepository->paginateWithSearchAndSort($search, $sort);
    }

    public function create(array $data, ?UploadedFile $image = null): Store
    {
        if ($image) {
            $data['image'] = $this->imageUploadService->uploadImage($image, 'uploads/store/');
        }
        return $this->storeRepository->create($data);
    }

    public function update(Store $store, array $data, ?UploadedFile $image = null): void
    {
        if ($image) {
            $store->image = $this->imageUploadService->uploadImage($image, 'uploads/store/');
        }
        if (isset($data['name'])) {
            $store->name = $data['name'];
        }
        $this->storeRepository->save($store);
    }

    public function delete(Store $store): void
    {
        $this->storeRepository->delete($store);
    }

    public function listTrashed()
    {
        return $this->storeRepository->onlyTrashedPaginate();
    }

    public function restore(int $id): void
    {
        $store = $this->storeRepository->findWithTrashed($id);
        $this->storeRepository->restore($store);
    }
}
