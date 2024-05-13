<?php

namespace App\Http\Controllers\API\V1\Admin\Blog;

use App\Http\Controllers\ResponseController;
use App\Http\Requests\API\Admin\BlogCategory\BlogCategoryStoreRequest;
use App\Http\Requests\API\Admin\BlogCategory\BlogCategoryupdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Storage;


class BlogCategoryController extends ResponseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 4) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $data['blogcategory'] = BlogCategory::all();
        return $this->jsonResponse(data: $data, message: 'All blogcategory list!!', code: 200);
   
    }

    public function store(BlogCategoryStoreRequest $request){
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 2) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        $blogC = BlogCategory::create($request->validated());
        if($request->hasFile('image')){
            $imageName = time().$request->image->getClientOriginalName();
            //store image in storage
            $path = Storage::putFileAs('public/uploads/blogCategory',$request->image,$imageName);
            $blogC->update(['image' => $path]);
        }
        $data['blogCategory'] = $blogC;
        return $this->jsonResponse(data: $data, message: 'blog category added !', code: 200);    
    }

    public function update($id,BlogCategoryupdateRequest $request){
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 2) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        $blogC = BlogCategory::findOrFail($id);
        $old_image = $blogC->image;
        $blogC->update($request->validated());
        if($request->hasFile('image')){
            if($old_image){
                Storage::delete($old_image);
            }
            $imageName = time().$request->image->getClientOriginalName();
            //store image in storage
            $path = Storage::putFileAs('public/uploads/blogCategory',$request->image,$imageName);
            $blogC->update(['image' => $path]);
        }
        return $blogC;
    }
}
