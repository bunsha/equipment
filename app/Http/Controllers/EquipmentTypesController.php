<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\EquipmentHistory;
use App\EquipmentStatus;
use App\EquipmentType;
use App\Http\Traits\GazingleCrud;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EquipmentTypesController extends Controller
{

    use GazingleCrud;
    const MODEL = EquipmentType::class;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request){
        parent::__construct($request);
    }
}
