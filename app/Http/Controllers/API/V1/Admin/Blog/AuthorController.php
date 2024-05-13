<?php

namespace App\Http\Controllers\API\V1\Admin\Blog;

use App\Http\Controllers\ResponseController;
use App\Http\Requests\API\Admin\Author\AuthorStoreRequest;
use App\Http\Requests\API\Admin\Author\AuthorUpdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Author;
use App\Traits\HasBulkActions;
use App\Traits\HasImageActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AuthorController extends ResponseController
{
    use HasBulkActions, HasImageActions;

    public static $model = Author::class;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getmodel()
    {
        return static::$model;
    }

    public function index()
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 4) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $data['author'] = Author::all();
        return $this->jsonResponse(data: $data, message: 'All Authors list!!', code: 200);
    }
    public function store(AuthorStoreRequest $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 4) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $author = Author::create($request->validated());
        if ($request->hasFile('image')) {
            $imageName = time() . $request->image->sgetClientOriginalName();
            //store image in storage
            $path = Storage::putFileAs('public/uploads/author', $request->image, $imageName);
            $author->update(['image' => $path]);
        }
        $data['author'] = $author;
        return $this->jsonResponse(data: $data, message: 'Author added', code: 200);
    }
    public function show($id)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 4) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $data['author'] = Author::findOrFail($id);
        return $this->jsonResponse(data: $data, message: 'Author added', code: 200);
    }
    public function update($id, AuthorUpdateRequest $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 4) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $old_author = Author::findOrFail($id);
        $old_image = $old_author->image;
        $old_author->update($request->validated());
        if ($request->hasFile('image')) {
            if ($old_image) {
                Storage::delete($old_image);
            }
            $imageName = time() . $request->image->getClientOriginalName();
            //store image in storage
            $path = Storage::putFileAs('public/uploads/author', $request->image, $imageName);
            $old_author->update(['image' => $path]);
        }
        $data['author'] = $old_author;
        return $this->jsonResponse(data: $data, message: 'Author added', code: 200);
    }

    public function test(Request $request)
    {
        // return 1;
        // return dd($request);
        $author = Author::findOrFail(1);
        if ($request->hasFile('image')) {
            return $this->storeImage($author, $request->image, 'public/uploads/test', 'image');
        }
        return 0;
    }
}
