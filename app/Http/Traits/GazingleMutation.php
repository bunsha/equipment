<?php

namespace App\Http\Traits;

use App\Events\GazingleCrud\ModelCreatedEvent;
use App\Events\GazingleCrud\ModelDeletedEvent;
use App\Events\GazingleCrud\ModelPurgedEvent;
use App\Events\GazingleCrud\ModelRestoredEvent;
use App\Events\GazingleCrud\ModelUpdatedEvent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

trait GazingleMutation {


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request){
        $model = self::MODEL;
        $this->item = new $model();
        if($this->_isSearchRequest($this->item)){
            $items = $this->_search($request);
        }else{
            $items = $this->item;
        }
        return $this->returnParsed($items, $request);
    }


    /**
     * Update model instance according to mutation mapper
     *
     * @return Model
     */
    public function applyInternalMutations($item, $account_id = true){
        $mutationModel = self::MUTATION_MODEL;
        $model = new $mutationModel();
        if($account_id){
            $internalMutations = $model::where('account_id', $account_id)->whereNull('uses_external_value')->get();
        }else{
            $internalMutations = $model::where('uses_external_value', '<>', 1)->get();
        }
        foreach($internalMutations as $mutation){
            $item[$mutation->name] = null;
        }

        foreach($item->meta as $metaKey => $metaValue){
            if(is_null($item[$metaKey]))
                $item[$metaKey] = $metaValue;
        }

        return $item;
    }

}