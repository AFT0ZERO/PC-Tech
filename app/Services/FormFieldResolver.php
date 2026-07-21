<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormFieldResolver
{
    private const EXCLUDED_PRODUCT_FIELDS = ['id', 'category_id', 'description', 'created_at', 'updated_at', 'deleted_at'];
    private const EXCLUDED_SPEC_FIELDS = ['product_id'];

    public function resolve(Category $category): array
    {
        $result = [
            'product_fields' => $this->getModelFields(\App\Models\Product::class, 'products', self::EXCLUDED_PRODUCT_FIELDS),
            'spec_fields'    => [],
            'specs_table'    => $category->specs_table,
            'open_db_name'   => $category->open_db_name,
            'category_name'  => $category->name,
        ];

        if ($category->specs_table) {
            $modelClass = $this->tableToModel($category->specs_table);
            if ($modelClass && class_exists($modelClass)) {
                $result['spec_fields'] = $this->getModelFields($modelClass, $category->specs_table, self::EXCLUDED_SPEC_FIELDS);
            }
        }

        return $result;
    }

    public function tableToModel(string $table): ?string
    {
        $className = 'App\\Models\\' . Str::studly(Str::singular($table));
        return class_exists($className) ? $className : null;
    }

    public function getModelFields(string $modelClass, string $table, array $exclude): array
    {
        $fillable = (new $modelClass)->getFillable();
        $columns = $this->getTableColumns($table);

        $fields = [];
        foreach ($fillable as $field) {
            if (in_array($field, $exclude, true)) {
                continue;
            }

            $colInfo = $columns[$field] ?? null;

            $fields[] = [
                'name'     => $field,
                'label'    => Str::title(str_replace('_', ' ', $field)),
                'type'     => $colInfo ? $this->columnTypeToInput($colInfo['type']) : 'text',
                'required' => $colInfo ? !$colInfo['nullable'] : false,
            ];
        }

        return $fields;
    }

    private function getTableColumns(string $table): array
    {
        $db = DB::connection()->getDatabaseName();
        $rows = DB::select(
            'SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
            [$db, $table]
        );

        $columns = [];
        foreach ($rows as $row) {
            $columns[$row->COLUMN_NAME] = [
                'type'     => $row->DATA_TYPE,
                'nullable' => $row->IS_NULLABLE === 'YES',
            ];
        }

        return $columns;
    }

    private function columnTypeToInput(string $dbType): string
    {
        $type = strtolower($dbType);

        if (str_contains($type, 'text') || str_contains($type, 'json')) {
            return 'textarea';
        }
        if (str_contains($type, 'int') || str_contains($type, 'decimal') || str_contains($type, 'float') || str_contains($type, 'double')) {
            return 'number';
        }

        return 'text';
    }
}
