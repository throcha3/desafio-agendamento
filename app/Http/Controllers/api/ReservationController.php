<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Models\Parameter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{

    public function getAll(Request $request){
        $start = $request->query('start');
        $end   = $request->query('end');

        $validator  = $request->validate([
            'start'    => 'required | date_format: "y-m-d"',
            'end'      => 'required | date_format: "y-m-d"'
        ]);

        if ($start == "" || $end == "")
            return response()->json(['message' => "Parametros invalidos"], Response::HTTP_BAD_REQUEST);

    	$return = ReservationService::getAll($start, $end);
    	//In case of exception, will return the exception message, otherwise will return requested data
    	if (isset($data['message']))
    		return response()->json(['message' => $return['message']], $return['http_response']);
    	
    	return response()->json(['reservations' => $return], Response::HTTP_OK);
    }
    /**
     * @param GET
     *      [date y-m-d] start
     *      [date y-m-d] end
     *      [integer] userid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllByUser(Request $request){
    	$user  = $request->query('userid');
    	$start = $request->query('start');
    	$end   = $request->query('end');

        $validator  = $request->validate([
            'userid'   => 'required | integer',
            'start'    => 'required | date_format: "y-m-d"',
            'end'      => 'required | date_format: "y-m-d"'
        ]);


    	$return = ReservationService::getAllByUser($user, $start, $end);
    	//In case of exception, will return the exception message, otherwise will return requested data
    	if (isset($data['message']))
    		return response()->json(['message' => $return['message']], $return['http_response']);
    	
    	return response()->json(['reservations' => $return], Response::HTTP_OK);
    }

    /**
     * @param GET
     *      [date y-m-d] start
     *      [date y-m-d] end
     *      [integer] spaceid
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAllBySpace(Request $request){
    	$spaceId  = $request->query('spaceid');
    	$start    = $request->query('start');
    	$end      = $request->query('end');

        $validator  = $request->validate([
            'spaceid'    => 'required | integer',
            'start'    => 'required | date_format: "y-m-d"',
            'end'      => 'required | date_format: "y-m-d"'
        ]);

    	$return = ReservationService::getAllBySpace($spaceId, $start, $end);
    	//In case of exception, will return the exception message, otherwise will return requested data
    	if (isset($data['message']))
    		return response()->json(['message' => $return['message']], $return['http_response']);
    	
    	return response()->json(['reservations' => $return], Response::HTTP_OK);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function store(Request $request){
    	$validator  = $request->validate(Reservation::RULES_RESERVATION);

    	$return = ReservationService::storeReservation($request);
    	return response()->json(['message' => $return['message']], $return['http_response']);
    }
    
}