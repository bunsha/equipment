<?php

namespace App\Http\Controllers;

use App\Http\Traits\GazingleApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
