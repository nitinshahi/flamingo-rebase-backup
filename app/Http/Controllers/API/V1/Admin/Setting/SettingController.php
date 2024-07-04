<?php

namespace App\Http\Controllers\API\V1\Admin\Setting;

use App\Http\Controllers\ResponseController;
use App\Http\Requests\API\Actions\BulkDelete;
use App\Http\Requests\API\Actions\BulkPermaDelete;
use App\Http\Requests\API\Actions\BulkRestore;
use App\Http\Requests\API\Admin\Setting\SettingStoreRequest;
use App\Http\Requests\API\Admin\Setting\SettingUpdateRequest;
use App\Http\Requests\APi\Admin\Setting\SettingEditRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Admin\UserResource;
use App\Models\GlobalSetting;

class SettingController extends ResponseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 4) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $this->authorize('view', $user);
        $data['user'] = new UserResource($user);
        $data['settings'] = GlobalSetting::all();
        return $this->jsonResponse(data: $data, message: 'All settings', code: 200);
    }

    public function show($id)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 4) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $this->authorize('viewAny', $user);
        $data['user'] = new UserResource($user);
        $data['settings'] = GlobalSetting::findOrFail($id);
        return $this->jsonResponse(data: $data, message: 'specific setting', code: 200);
    }

    public function store(SettingStoreRequest $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 2) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        $data['setting'] = $request->validated();
        GlobalSetting::create($request->validated());
        return $this->jsonResponse(data: $data, message: 'settings added successfully!!', code: 200);
    }

    public function editsettings($id, SettingEditRequest $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if (!$user->user_type->getPrecedence() == 1) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        $setting = GlobalSetting::findOrFail($id);
        if ($request->value_type != $setting->value_type) {
            $old_value_type = $setting->value_type;
            if ($setting->{$old_value_type . '_value'}) {
                if ($old_value_type == 'image' || $old_value_type == 'file') {
                    Storage::delete($setting->{$old_value_type . '_value'});
                }
            }
            $setting->{$old_value_type . '_value'} = null;
        }
        $setting->update($request->validated());
        $setting->save();

        $data['setting'] = $setting;
        return $this->jsonResponse(data: $data, message: 'settings updated successfully!!', code: 200);
    }

    public function update(SettingUpdateRequest $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 1) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        $setting = GlobalSetting::where('setting_key', $request->setting_key)->first();
        if (!$setting) {
            return $this->jsonResponse(data: $data, message: 'settings not found', code: 404);
        }
        $value_type = $setting->value_type;
        $setting->{$value_type . '_value'} = $request->setting_value;

        if ($value_type == 'image' || $value_type == 'file') {
            $imageName = time() . $request->setting_value->getClientOriginalName();
            //store image in storage
            $path = Storage::putFileAs('public/uploads/setting', $request->setting_value, $imageName);
            $setting->{$value_type . '_value'} = $path;
        }
        $setting->save();

        $data['setting'] = $setting;
        return $this->jsonResponse(data: $data, message: 'settings updated successfully!!', code: 200);
    }

    protected function bulkDelete(BulkDelete $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 2) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        if (count($request->bulk) > GlobalSetting::count()) {
            return $this->jsonResponse(data: $data, message: 'bulk array not valid', code: 422);
        }
        $softDelete = GlobalSetting::whereIn('id', $request->bulk)->get();
        if ($softDelete->count() < 1) {
            return $this->jsonResponse(data: $data, message: 'bulk array not valid', code: 422);
        }
        foreach ($softDelete as $delsetting) {
            $delsetting->delete();
        }
        $data['list'] = GlobalSetting::withTrashed()->get();
        return $this->jsonResponse(data: $data, message: 'settings deleted successfully');
    }
    protected function bulkRestore(BulkRestore $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if ($user->user_type->getPrecedence() > 2) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        $restoreSetting = GlobalSetting::onlyTrashed()->whereIn('id', $request->bulk)->get();
        if (!$restoreSetting->count()) {
            return $this->jsonResponse(data: $data, message: 'settings not found', code: 422);
        }
        foreach ($restoreSetting as $restore) {
            $restore->restore();
        }
        $data['list'] = GlobalSetting::withTrashed()->get();
        return $this->jsonResponse(data: $data, message: 'settings restored succesfully');
    }

    protected function bulkPermaDelete(BulkPermaDelete $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        $permaDelete = GlobalSetting::onlyTrashed()->whereIn('id', $request->bulk)->get();
        if (!$permaDelete->count()) {
            return $this->jsonResponse(data: $data, message: 'settings not found', code: 422);
        }
        foreach ($permaDelete as $permadelete) {
            if ($permadelete->value_type == 'image' || $permadelete->value_type == 'file') {
                $value_type = $permadelete->value_type;
                Storage::delete($permadelete->{$value_type . '_value'});
            }
            $permadelete->forceDelete();
        }
        $data['list'] = GlobalSetting::withTrashed()->get();
        return $this->jsonResponse(data: $data, message: 'settings deleted Permanently!!');
    }
}
