<?php

namespace App\Traits;

Trait HasBulkActions
{
   
    public function txts(){
        $model = $this->getmodel();
        return $model::all();
    }

}