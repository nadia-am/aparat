<?php

namespace App\Http\Controllers;

use App\Http\Requests\category\CreateCategoryRequest;
use App\Http\Requests\category\ListCategoryRequest;
use App\Http\Requests\category\UploadCategoryBannerRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(ListCategoryRequest $request)
    {
        return CategoryService::getAllCategories($request);
    }

    public function my(ListCategoryRequest $request)
    {
        return CategoryService::getMyCategories($request);
    }

    public function uploadBanner(UploadCategoryBannerRequest $request)
    {
        return CategoryService::UploadBannerService($request);
    }

    public function create(CreateCategoryRequest $request)//
    {
        return CategoryService::CreateCategoryService($request);
    }
}
