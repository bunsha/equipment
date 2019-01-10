<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\EquipmentHistory;
use App\EquipmentStatus;
use App\Http\Traits\GazingleCrud;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EquipmentStatusesController extends Controller
{

    use GazingleCrud;
    const MODEL = Equipment::class;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request){
        parent::__construct($request);
    }
}
