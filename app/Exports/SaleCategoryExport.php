<?php
namespace App\Exports;

use Modules\Sale\Entities\SaleCategory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SaleCategoryExport implements FromCollection, WithHeadings
{
    protected $categories;

    public function __construct(Collection $categories)
    {
        $this->categories = $categories;
    }

    public function headings(): array
    { 
        return ['ID', 'Name', 'Image', 'Parent', 'Status', 'Published'];
    }

    public function collection()
    {
        return $this->categories->map(function ($category) {
            $parentName = $category->parent ? $category->parent->name : '';

            return [
                $category->id,
                $category->name,
                $category->image,
                $parentName,
                $category->is_active == 1 ? 'Active' : 'Inactive',
                dateFormatInPlainText($category->created_at)
            ];
        });
    }
}
