<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\UploadedFile;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ImageUploadService $imageUploadService
    ) {
    }

    public function list(?string $search = null)
    {
        return $this->categoryRepository->paginateWithSearch($search);
    }

    public function create(array $data, ?UploadedFile $image = null): Category
    {
        if ($image) {
            $data['image'] = $this->imageUploadService->uploadImage($image, 'uploads/category/');
        }
        return $this->categoryRepository->create($data);
    }

    public function update(Category $category, array $data, ?UploadedFile $image = null): void
    {
        if ($image) {
            $category->image = $this->imageUploadService->uploadImage($image, 'uploads/category/');
        }
        if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        $this->categoryRepository->save($category);
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    public function listTrashed()
    {
        return $this->categoryRepository->onlyTrashedPaginate();
    }

    public function restore(int $id): void
    {
        $category = $this->categoryRepository->findWithTrashed($id);
        $this->categoryRepository->restore($category);
    }
}
