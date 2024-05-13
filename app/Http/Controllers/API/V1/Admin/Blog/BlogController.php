<?php

namespace App\Http\Controllers\API\V1\Admin\Blog;

use App\Http\Controllers\ResponseController;
use App\Http\Requests\API\Admin\Blog\BlogStoreRequest;
use App\Http\Requests\API\Admin\Blog\BlogUpdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends ResponseController
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
        $data['blog'] = Blog::all();
        return $this->jsonResponse(data: $data, message: 'All blog list!!', code: 200); 
    }

    public function store(BlogStoreRequest $request){
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 2) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        $blog = Blog::create($request->validated());
        $blog->authors()->sync($request->authors);
        $blog->blogcategories()->sync($request->blogCategories);
        
        if($request->hasFile('avatar_image')){
            $imageName = time().$request->avatar_image->getClientOriginalName();
            //store image in storage
            $path = Storage::putFileAs('public/uploads/blog',$request->avatar_image,$imageName);
            $blog->update(['avatar_image' => $path]);
        }
        if($request->hasFile('banner_image')){
            $imageName = time().$request->banner_image->getClientOriginalName();
            //store image in storage
            $path = Storage::putFileAs('public/uploads/blog',$request->banner_image,$imageName);
            $blog->update(['banner_image' => $path]);
        }
        $data['blog'] = $blog;
        return $this->jsonResponse(data: $data, message: 'blog category added !', code: 200);
    }

    public function update($id,BlogUpdateRequest $request){
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 2) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        // return $request;
        $blogC = Blog::findOrFail($id);
        $blogC->authors()->sync($request->authors);
        $blogC->blogcategories()->sync($request->blogCategories);
        return $request;
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
