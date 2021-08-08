<?php


namespace App\Services;


use App\Http\Requests\category\CreateCategoryRequest;
use App\Http\Requests\category\ListCategoryRequest;
use App\Http\Requests\category\UploadCategoryBannerRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        return auth()->user()->categories;
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
            Log::error($e);
            return response(['message'=>'خطایی رخ داده است!'],500);
        }
    }

    public static function CreateCategoryService(CreateCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            if (!empty($request->banner_id)){
                $banner_name = auth()->id() . '/' .$request->banner_id ;
                Storage::disk('category')->move( 'tmp/' . $request->banner_id ,  $banner_name);
            }
            $category = auth()->user()->categories()->create($data);
            DB::commit();

            return response($category,200);

        }catch (\Exception $e){
           Log::error($e);
           return response(['message'=>'خطایی رخ داده است'],500);
        }
    }
}
