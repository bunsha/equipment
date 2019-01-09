<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\EquipmentHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EquipmentController extends Controller
{

    /*
     * @question : Will we allow to c soft-deleted entity on get/put requests ?
     */
    public $item;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request){
        $this->item = new Equipment();
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
        $this->item = Equipment::withTrashed()->findOrFail($id);
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
     * @ToDo deal with restore UNIQUE ID collision
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
        $this->item->forceDelete();
        return $this->success($this->item);
    }

    /**
     * Get current item history from DB entity. Ignores soft-deletes
     *
     * @return EquipmentHistory
     */
    protected function _getHistoryItem($service, $service_id){
        $item = EquipmentHistory::where('equipment_id', $this->item->id)
            ->where('service_id', $service_id)
            ->where('service', $service)
            ->orderBy('created_at', 'desc')
            ->first();
        return $item;
    }

    /**
     * Get specific resource history.
     * @return Response
     */
    public function getHistory($id){
        $this->item = $this->_getById($id);
        return $this->success($this->item->history);
    }

    /**
     * Get specific resource history, which is not detached
     * @return Response
     */
    public function getAttachedHistory($id){
        $this->item = $this->_getById($id);
        return $this->success($this->item->history()->whereNull('detached_at')->get());
    }

    /**
     * Get specific resource history, which is detached
     * @return Response
     */
    public function getDetachedHistory($id){
        $this->item = $this->_getById($id);
        return $this->success($this->item->history()->whereNotNull('detached_at')->get());
    }

    /**
     * Attach specific resource to a service.
     * @return Response
     */
    public function attach(Request $request, $id){
        $this->item = $this->_getById($id);
        $request->merge(['equipment_id' => $id]);
        if(!$request->has('attached_at')){
            $request->merge(['attached_at' => Carbon::now()]);
        }
        $this->validate($request, [
            'service_id' => 'required|integer',
            'equipment_id' => 'required|integer',
            'user_id' => 'integer',
            'service' => 'required|string',
            'attached_at' => 'date',
        ]);
        try{
            $historyItem = $this->_getHistoryItem($request->service, $request->service_id);
            if(!$historyItem || $historyItem->detached_at){
                $result = $this->item->history()->create($request->except('detached_at'));
                return $this->success($result);
            }
            return $this->wrongData('Equipment is already attached');
        }catch(\Exception $e){
            return $this->serverError('Something went wrong. Please check a documentation');
        }
    }

    /**
     * Detach specific resource to a service.
     * @return Response
     */
    public function detach(Request $request, $id){
        $request->merge(['equipment_id' => $id]);
        $this->item = $this->_getById($id);
        $this->validate($request, [
            'service_id' => 'required|integer',
            'equipment_id' => 'required|integer',
            'user_id' => 'integer',
            'service' => 'required|string',
            'detached_at' => 'date',
        ]);
        try{
            $result = $this->_getHistoryItem($request->service, $request->service_id);
            if($result){
                if($result->detached_at)
                    return $this->wrongData('Equipment is already detached');
            }else{
                return $this->wrongData('Equipment is not attached');
            }
            $result->fill(['detached_at' => ($request->has('detached_at'))? $request->detached_at : Carbon::now()])->save();

            return $this->success($result);
        }catch(QueryException $e){
            return $this->serverError('Something went wrong. Please check a documentation');
        }
    }
}
