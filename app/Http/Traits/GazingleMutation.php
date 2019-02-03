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
     * Update model instance with internal data,  according to mutation mapper
     *
     * @return array
     */
    public function applyInternalMutations($items, $account_id = true){
        $mutationModel = self::MUTATION_MODEL;
        $model = new $mutationModel();
        if($account_id){
            $internalMutations = $model::where('account_id', $account_id)->whereNull('uses_external_value')->get();
        }else{
            $internalMutations = $model::whereNull('uses_external_value')->get();
        }

        foreach($items as $item){
            foreach($internalMutations as $mutation){
                $item[$mutation->name] = null;
            }
            if($item->meta){
                foreach($item->meta as $metaKey => $metaValue){
                    if(is_null($item[$metaKey]))
                        $item[$metaKey] = $metaValue;
                }
            }
        }
        return $items;
    }

    /**
     * Update model instance with external data, according to mutation mapper
     *
     * @return array
     */
    public function applyExternalMutations($items, $account_id = true){
        $mutationModel = self::MUTATION_MODEL;
        $model = new $mutationModel();
        if($account_id){
            $externalMutations = $model::where('account_id', $account_id)->whereNotNull('uses_external_value')->get();
        }else{
            $externalMutations = $model::whereNotNull('uses_external_value')->get();
        }

        foreach($items as $item){
            $services = [];
            foreach($externalMutations as $mutation){
                $services[] = $mutation->external['service'];
                $item[$mutation->name] = $mutation->external['service'];
            }
            $connections = $this->_getExternalConnections([$item->id], $services);
            foreach($externalMutations as $mutation){
                if(isset($connections[$item[$mutation->name]]))
                    $item[$mutation->name] =  $connections[$item[$mutation->name]];
                else
                    $item[$mutation->name] = null;
            }
        }
        return $items;
    }


    protected function _getExternalConnections($ids, $services, $exclude = []){
        $exclude = implode(',', $ids);
        $connectionModel = self::CONNECTION_MODEL;
        $this->connectionModel = new $connectionModel;
        $connections = $this->connectionModel
            ->whereIn('item_id', $ids)
            ->whereIn('service', $services);
        if(isset($this->request['exclude_mutation'])){
            $connections = $connections->whereNotIn('service', [$this->request['exclude_mutation']]);
        }

        if(!isset($this->request['with_detached'])){
            $connections = $connections->whereNull('detached_at');
        }
        $connections = $connections->get();
        //print_r($connections->toArray()) ;

        $includedServices = [];
        $includedObjects = [];
        foreach ($connections as $connectionItem){
            if(!isset($includedServices[$connectionItem->service])){
                $includedServices[$connectionItem->service] = [];
            }
            $includedServices[$connectionItem->service][] = $connectionItem->service_id;
        }
        foreach($includedServices as $serviceKey => $ids){
            try{
                //'exclude' => implode(',', $ids)
                $serviceResponse = $this->indexFrom($serviceKey, ['id' => implode(',', $ids), 'exclude_mutation' => $serviceKey]);
                if(isset($serviceResponse['data']))
                    $serviceResponse = $serviceResponse['data'];
            }catch(\Exception $exception){
                return $this->wrongData('Something went wrong with connection to '.$serviceKey.'. Please check out your connections table');
            }
            $includedObjects[$serviceKey] = $serviceResponse;
        }
        return $includedObjects;

    }






    /*
     * Setup a mutations for specific account
     */
    public function setupMutations(Request $request){
        $mutationModel = self::MUTATION_MODEL;
        $presetModel = self::PRESET_MODEL;
        if($request->account_id){
            $items = $presetModel::all();
            $newItems = [];
            foreach($items as $item){
                $exist = $mutationModel::where('name', $item->name)->where('account_id', $request->account_id)->first();
                if($exist){
                    //$exist->update($item->toArray());
                }else{
                    $item['account_id'] = $request->account_id;
                    try{
                        $mutation = $mutationModel::create($item->toArray());
                        $newItems[] = $mutation;
                    }catch(\Exception $exception){
                        return $this->wrongData("Something went wrong...\n ID of preset: ".$item->id);
                    }
                }
            }
            return $this->success($newItems, 'Equipment has been set-up for account '.$request->account_id.'. Added '.count($newItems).' mutation rules');
        }else{
            return $this->wrongData('Please provide an account_id field');
        }
    }
}