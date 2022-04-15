<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Category;

class Home extends Model
{
    use HasFactory;

    function _getLimitedCategories()
    {
        return (new Category())->_getLimitedCategoriesForFrontend();
    }

    function _getLimitedCategoriesWithProducts()
    {
        return (new Category())->_getLimitedCategoriesWithProductsForFrontend();
    }
}
