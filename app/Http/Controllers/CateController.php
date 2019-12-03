<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\SexCate;
use Illuminate\Support\Facades\DB;

class CateController extends Controller
{

    public function cateList()
    {
        $list = Category::select(DB::raw('id,name'))->get();
        return $this->successful('', ['list' => $list]);
    }
    
    public function cateListBySex($sex = 0)
    {
        $model = SexCate::with(['cate' => function ($query) {
            $query->select('id', 'name');
        }]);
        if($sex) $model->where('sex', $sex);
        $list = $model->get();
        return $this->successful('', ['list' => $list]);
    }
}
