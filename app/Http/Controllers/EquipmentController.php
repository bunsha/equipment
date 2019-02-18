<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Connection;
use App\EquipmentMutation;
use App\EquipmentPreset;
use App\Http\Traits\GazingleConnect;
use App\Http\Traits\GazingleCrud;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EquipmentController extends Controller
{
    use GazingleCrud;


    const MODEL = Equipment::class;
    const MUTATION_MODEL = EquipmentMutation::class;
    const CONNECTION_MODEL = Connection::class;
    const PRESET_MODEL = EquipmentPreset::class;
    public $token = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request){
        parent::__construct($request);
    }

    /*
     * Temporary function to play with new features
     */
    public function play(Request $request){
        //return $this->indexFrom('equipment', ['name_like' => 'nostrum']);
        //return $this->getFrom('equipment', 13123123123);
        //return $this->createFrom('equipment', 1);
        //return $this->updateFrom('equipment', 1, ['name' => 'nostrum1']);
        //return $this->deleteFrom('equipment', 1);
        //return $this->restoreFrom('equipment', 1);
        //return $this->purgeFrom('equipment', 123);
    }
}
