<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\EquipmentHistory;
use App\EquipmentStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EquipmentStatusesController extends Controller
{

    public $item;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request){
        $this->item = new EquipmentStatus();
        if($request->has('columns')){
            $columns = explode(",",str_replace(' ', '', $request->get('columns')));
            foreach ($columns as $i => $column) {
                if(!in_array($columns[$i], $this->item->searchable)){
                    unset($columns[$i]);
                }
            }
            $request['columns'] = implode(',', $columns);
        }
        parent::__construct($request);
    }

    /**
     * Search in model by filters.
     *
     * @return Builder
     */
    protected function _search(Request $request){
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
     * @return Response
     */
    protected function _getById($id){
        $this->item = EquipmentStatus::withTrashed()->findOrFail($id);
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
        $this->validate($request, $this->item->rules());
        $this->item = $this->item->create($request->all());
        return $this->get($request, $this->item->id);
    }

    /**
     * Update a specific resource.
     * @return Response
     */
    public function update(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->validate($request, $this->item->rules());
        $this->item->fill($request->all())->save();
        return $this->success($this->item);
    }

    /**
     * Soft deletes a specific resource.
     * @return Response
     */
    public function delete(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->item->delete();
        return $this->success($this->item);
    }

    /**
     * Restore a soft-deleted specific resource.
     * @return Response
     */
    public function restore(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->item->restore();
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
        }catch(QueryException $exception){
            return $this->wrongData('Unable to purge attached to an equipment(s) item. Please Detach all connections first');
        }

        return $this->success($this->item);
    }
}
