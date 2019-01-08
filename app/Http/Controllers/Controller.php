<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public $time;
    public $request;
    public $request_names = [];
    public $search_params = [];
    public $maxResults = 200;

    public function __construct(Request $request){
        $this->time = Carbon::now();
        $this->request = $request->all();
        foreach($this->request as $param => $value){
            $this->request_names[] = $param;
        }
    }

    /**
     * Determine if request contains a filters
     * @param Model $model
     * @return bool
     */
    protected function _isSearchRequest(Model $model){
        $isSearchRequest = false;
        foreach($model->searchable as $param){
            if (in_array($param, $this->request_names)) {
                $isSearchRequest = true;
            }
            if (in_array($param.'_like', $this->request_names)) {
                $isSearchRequest = true;
            }
        }
        if(!empty($this->request['meta']))
            $isSearchRequest = true;
        return $isSearchRequest;
    }

    /**
     * Search in Model by Model Searchable
     * @param Builder $items
     * @param Model $model
     * @return Builder
     */
    protected function _searchInModel(Model $model, Builder $items){
        foreach($model->searchable as $param){
            if(isset($this->request[$param]))
                $items = $items->where($param, $this->request[$param]);
            if(isset($this->request[$param.'_like']))
                $items = $items->where($param, 'like', '%'.$this->request[$param.'_like'].'%');
        }
        return $items;
    }

    /**
     * Send "Success" response
     * @return Response
     */
    public function success($data, $message = 'OK'){
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'time' => $this->time
        ]);
    }

    /**
     * Send "not Found" response
     * @return Response
     */
    public function notFound(){
        return response()->json([
            'success' => false,
            'message' => 'Not found',
            'time' => $this->time
        ], 404);
    }

    /**
     * Send "Wrong Data" response
     * @param string $message
     * @return Response
     */
    public function wrongData($message){
        return response()->json([
            'success' => false,
            'message' => $message,
            'time' => $this->time
        ], 412);
    }

    /**
     * Send "Server Error" response
     * @param string $message
     * @return Response
     */
    public function serverError($message){
        return response()->json([
            'success' => false,
            'message' => $message,
            'time' => $this->time
        ], 500);
    }

    /*
     * Parse and returns Api request depending on options provided to request
     * @param collection $items
     * @param Request $request
     * @return Response
     */
    public function returnParsed($items, $request){
        try{
            if($request->has('columns')){
                $columns = explode(",",str_replace(' ', '', $request->get('columns')));
                $items = $items->select($columns);
            }
            if($request->has('with_trashed')){
                $items = $items->withTrashed();
            }
            if($request->has('only_trashed')){
                $items = $items->onlyTrashed();
            }
            if ($request->has('just_count')){
                $count = $items->count();
                return $this->success(['total' => $count]);
            }
            if($request->has('limit')){
                $items = $items->take($request->limit);
            }else{
                $items = $items->take($this->maxResults);
            }
            if($request->has('paginate'))
                return $this->success($items->paginate($request->paginate)->appends($request->all()));
            else{
                $count = $items->count();
                if($count <= $this->maxResults){
                    return $this->success($items->get());
                }else{
                    if($request->has('limit')){
                        return $this->success($items->get());
                    }else{
                        return $this->success($items->paginate($this->maxResults)->appends(['total' => $count]));
                    }
                }
            }
        }catch(\Exception $ex){
            //return $this->serverError($ex->getMessage());
            return $this->serverError('Wrong filters provided. Please check documentation');
        }
    }
}
