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

trait GazingleCrud {

    use GazingleApi;


    public $item;
    public $model;


    /**
     * Search in model by filters.
     *
     * @return Builder
     */
    protected function _search(Request $request){
        $model = self::MODEL;
        $this->item = new $model();
        $items = $this->item->whereNotNull('id');
        $items = $this->_searchInModel($this->item, $items);
        return $items;
    }

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
     * Set current item as DB entity. Ignores soft-deletes
     *
     * @return Model
     */
    protected function _getById($id){
        $model = self::MODEL;
        $this->item = new $model();
        $this->item = $model::withTrashed()->findOrFail($id);
        return $this->item;
    }

    /**
     * Display a specific resource.
     *
     * @return Response
     */
    public function get(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->item =  $this->applyInternalMutations([$this->item])[0];;
        return $this->success($this->item);
    }

    /**
     * Store a specific resource.
     * @return Response
     */
    public function store(Request $request){
        $model = self::MODEL;
        $this->item = new $model();
        $this->validate($request, $this->item->createRules());
        $this->item = $this->item->create($request->all());
        $this->item = $this->_getById($this->item->id);
        $this->item = $this->applyInternalMutations([$this->item])[0];
        event(new ModelCreatedEvent($this->item));
        return $this->success($this->item);
    }

    /**
     * Update a specific resource.
     * @return Response
     */
    public function update(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->validate($request, $this->item->updateRules());
        $this->item->fill($request->all());
        $allMeta = $this->item->meta;
        foreach($this->item->meta as $metaKey => $metaValue){
            if(array_key_exists($metaKey, $request->all())){
                $allMeta[$metaKey] = $request[$metaKey];
                $this->item->meta = $allMeta;
            }
        }
        $this->item->save();
        $this->item = $this->applyInternalMutations([$this->item])[0];
        event(new ModelUpdatedEvent($this->item));
        return $this->get($request, $this->item->id);
    }

    /**
     * Soft deletes a specific resource.
     * @return Response
     */
    public function delete(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->item->delete();
        $this->item = $this->applyInternalMutations([$this->item])[0];
        event(new ModelDeletedEvent($this->item));
        return $this->success($this->item);
    }

    /**
     * Restore a soft-deleted specific resource.
     * @ToDo deal with restore UNIQUE ID collision
     * @return Response
     */
    public function restore(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->item->restore();
        $this->item = $this->applyInternalMutations([$this->item])[0];
        event(new ModelRestoredEvent($this->item));
        return $this->success($this->item);
    }

    /**
     * Purge a specific resource.
     * @return Response
     */
    public function destroy(Request $request, $id){
        $this->item = $this->_getById($id);
        try{
            $this->item->forceDelete();
            $this->item = $this->applyInternalMutations([$this->item])[0];
            event(new ModelPurgedEvent($this->item));
        }catch(QueryException $exception){
            return $this->wrongData('Unable to purge item. Please Detach all connections first');
        }
        return $this->success($this->item);
    }
}