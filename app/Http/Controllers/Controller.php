<?php

namespace App\Http\Controllers;

use App\Http\Traits\GazingleApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use GazingleApi;

    public function __construct(Request $request){
        $this->time = Carbon::now();
        $this->request = $request->all();
        foreach($this->request as $param => $value){
            $this->request_names[] = $param;
        }
    }
}
