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
        $keywords = $this->request->get('keywords', '');
        $model = Category::select(DB::raw('id,name'));
        if($keywords) $model->where('name', 'like', "%$keywords%");
        $list = $model->get();
        return $this->successful('', ['list' => $list, 'keywords' => $keywords]);
    }
    
    public function cateListBySex($sex = 0)
    {
        $keywords = $this->request->get('keywords', '');
        $model = SexCate::with(['cate' => function ($query) use ($keywords) {
            if($keywords) $query->select('id', 'name')->where('name', 'like', "%$keywords%");
            else $query->select('id', 'name');
        }]);
        if($sex) $model->where('sex', $sex);
        $list = $model->get();
        return $this->successful('', ['list' => $list, 'keywords' => $keywords]);
    }
}
