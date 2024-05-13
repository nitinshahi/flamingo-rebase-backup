<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


Trait HasImageActions
{

    public function storeImage(Model $record, $request,string $path,string $imageField){
        return $record;
        $imageName = time().$request->getClientOriginalName();
        $path = Storage::putFileAs($path,$request,$imageName);
        return $record->update([$imageField => $path]);
    }
}