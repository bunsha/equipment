<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\EquipmentHistory;
use App\EquipmentMutation;
use App\EquipmentStatus;
use App\Http\Traits\GazingleCrud;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EquipmentMutationsController extends Controller
{

    use GazingleCrud;
    const MODEL = EquipmentMutation::class;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request){
        parent::__construct($request);
    }
}
