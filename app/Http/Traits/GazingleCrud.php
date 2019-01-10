<?php

namespace App\Http\Traits;

use App\Events\ModelCreatedEvent;
use App\Events\ModelDeletedEvent;
use App\Events\ModelPurgedEvent;
use App\Events\ModelRestoredEvent;
use App\Events\ModelUpdatedEvent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        $this->item->fill($request->all())->save();
        event(new ModelUpdatedEvent($this->item));
        return $this->success($this->item);
    }

    /**
     * Soft deletes a specific resource.
     * @return Response
     */
    public function delete(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->item->delete();
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
            event(new ModelPurgedEvent($this->item));
        }catch(QueryException $exception){
            return $this->wrongData('Unable to purge item. Please Detach all connections first');
        }
        return $this->success($this->item);
    }
}