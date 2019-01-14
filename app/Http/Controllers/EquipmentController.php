<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\EquipmentHistory;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request){
        parent::__construct($request);
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
