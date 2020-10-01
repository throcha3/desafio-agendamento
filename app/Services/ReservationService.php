<?php

namespace App\Services;

use App\Models\ValidationProduto;
use App\Repositories\ProdutoRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Parameter;
use Carbon\Carbon;
use App\Models\Reservation;

class ReservationService
{
    /**
     * @param Request $request
     * @return array('message', 'http_response_code') $response
     */
    public static function storeReservation(Request $request){
        $return = self::validateDate($request->start,$request->end);
            if($return['http_response'] == Response::HTTP_BAD_REQUEST) return $return;
        $return = self::checkParameterized($request->start,$request->end);
            if($return['http_response'] == Response::HTTP_BAD_REQUEST) return $return;
        $return = self::validateConflicts($request->start, $request->end, $request->space_id, $request->user_id);
            if($return['http_response'] == Response::HTTP_BAD_REQUEST) return $return;
        //dd($request);
        $dif = self::getDateDif($request->start,$request->end);
        $agora = Carbon::parse($request->start);
        for ($i=0; $i <= $dif ; $i++) { 
            try {
                $new = new Reservation([
                    'user_id' =>  $request->user_id,
                    'space_id' => $request->space_id,
                    'day'    => $agora,
                ]);
            
                $new->save();
                $agora->add(1, 'day');
                //return response()->json($produto, Response::HTTP_OK);
            } catch (QueryException $exception) {
                dd($exception);
                return array('message' => 'Erro de conexão com o banco de dados', 'http_response' => Response::HTTP_INTERNAL_SERVER_ERROR);
                break;
            }
        }

        return array('message' => "Successo ao inserir reservas.", 'http_response' => Response::HTTP_OK);
    }

    private static function validateDate($start, $end){
        $s  = Carbon::parse($start);
        $e  = Carbon::parse($end);
        if($s > $e) 
            return array('message' => "Erro. Data inicial nao pode ser maior que a final.", 'http_response' => Response::HTTP_BAD_REQUEST);
        else 
            return array('message' => "Tudo certo!.", 'http_response' => Response::HTTP_OK);
    }

    private static function checkParameterized($start, $end){
        $param = Parameter::find(1);
        if ($param == null) 
            return array('message' => "Erro. Nao foi possivel buscar parametros.", 'http_response' => Response::HTTP_BAD_REQUEST);
        //Check if there's enough antecedence as configured by system admin
        $antecedence = Carbon::now()->diffInDays($start);
        if($antecedence < ($param->antecedence_days-1))
            return array('message' => "Erro. O agendamento precisa ter no mínimo 10 dias de antecedencia.", 'http_response' => Response::HTTP_BAD_REQUEST);
        //Check if the reservation has at least X days as configured by system admin
        $dif = self::getDateDif($start, $end);
        if($dif < ($param->minimum_days-1))
            return array('message' => "Erro. O agendamento nao pode ser menor do que 5 dias.", 'http_response' => Response::HTTP_BAD_REQUEST);

        return array('message' => "Tudo certo!.", 'http_response' => Response::HTTP_OK);
    }

    private static function validateConflicts($start, $end, $spaceId, $userId){
        //Check another reservation for the same space in the same date
        $conflict = Reservation::getBySpace($start,$end,$spaceId);
        if($conflict->count() > 0) 
            return array('message' => "Erro. Conflito de datas neste espaco.", 'http_response' => Response::HTTP_BAD_REQUEST);
        //Check if the user has another reservation in the same date
        $conflict = Reservation::getByUser($start,$end,$userId);
        if($conflict->count() > 0) 
            return array('message' => "Erro. Ja existe outro agendamento para esse usuario na mesma data.", 'http_response' => Response::HTTP_BAD_REQUEST);
    }

    public static function getDateDif($start, $end){
        return Carbon::parse($start)->diffInDays($end);
    }

    public static function getAll($start, $end){
        try {
            
            $data = Reservation::getAll($start, $end);
            return $data;
        } catch (QueryException $exception) {
            return array('message' => 'Internal Error'. $exception, 'http_response' => Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function getAllByUser($user_id, $start, $end){
        try {
            $data = Reservation::getByUser($user_id, $start, $end);
            return $data;
        } catch (QueryException $exception) {
            return response()->json(['message' => 'Internal Error'. $exception], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function getAllBySpace($spaceId, $start, $end){
        try {
            $data = Reservation::getBySpace($spaceId, $start, $end);
            return $data;
        } catch (QueryException $exception) {
            return response()->json(['message' => 'Internal Error'. $exception], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}