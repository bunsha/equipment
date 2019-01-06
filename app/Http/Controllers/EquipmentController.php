<?php

namespace App\Http\Controllers;

use App\Equipment;
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
        if(is_numeric($id)){
            try{
                $this->item =  $this->item->withTrashed()->find($id);
                if($this->item){
                    return $this->success($this->item);
                }
                return $this->notFound();
            }catch(\Illuminate\Database\QueryException $ex){
                return $this->serverError("Something went wrong. Please try again later");
            }
        }
        return $this->wrongData('The "id" needs to be integer');
    }

    /**
     * Store a specific resource.
     * @return Response
     */
    public function store(Request $request){
        $this->validate($request, $this->item->rules());
        if($result = $this->item->create($request->all())){
           return $this->get($request, $result->id);
        }
        return $this->serverError("Something went wrong. Please try again later");
    }

    /**
     * Update a specific resource.
     * @return Response
     */
    public function update(Request $request, $id){
        $this->item = $this->_getById($id);
        $this->validate($request, $this->item->rules());
        if($this->item->fill($request->all())->save()){
            return $this->success($this->item);
        }
        return $this->serverError("Something went wrong. Please try again later");
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
        $this->item->forceDelete();
        return $this->success($this->item);
    }
}
