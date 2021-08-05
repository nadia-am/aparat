<?php


namespace App\Services;


use App\Http\Requests\category\ListCategoryRequest;
use App\Http\Requests\category\UploadCategoryBannerRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService  extends BaseService
{


    public static function getAllCategories(ListCategoryRequest $request)
    {
        $categories = Category::all();
        return $categories;
    }

    public static function getMyCategories(ListCategoryRequest $request)
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return $categories;
    }
    public static function create(ListCategoryRequest $request)
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return $categories;
    }

    public static function UploadBannerService(UploadCategoryBannerRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName =  time() . Str::random(10) . '-banner';
            Storage::disk('category')->put( '/tmp/' . $fileName,$banner->get() );

            return response([  'banner'=> $fileName ],200);
        }catch (\Exception $e){
            return response(['message'=>'خطایی رخ داده است!'],500);
        }
    }
}
