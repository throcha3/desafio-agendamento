<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Space;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
    	'user_id',
    	'space_id',
    	'day'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public const RULES_RESERVATION = [
        'user_id'  => 'required | integer',
        'space_id' => 'required | integer',
        'start'    => 'required | date_format: "y-m-d"',
        'end'      => 'required | date_format: "y-m-d"'
    ];

    /**
     * Return the User model relative to current reservation
     * @return [User::class] 
     */
    public function user(){
    	return $this->belongsTo(User::class);
    }
    /**
     * Return the Space model relative to current reservation
     * @return [Space::class] 
     */
    public function space(){
    	return $this->belongsTo(Space::class);
    }

    /**
     * Return all reservations with related models in the object
     * @return [Reservation::class] 
     */
    public static function getAll($start, $end){
        $query = Reservation::with('user','space')->whereBetween('day', [$start, $end])->paginate(50)->appends(request()->query());
        return $query;
    }

    /**
     * Get all reservation in certain period and space id. That's usefull to check conflicts too.
     * @param  [date] $start
     * @param  [date] $end
     * @param  [integer] $spaceId
     * @return [Reservation::class] $query
     */
    public static function getBySpace( $spaceId, $start, $end){
        //DB::enableQueryLog(); 
        $query = Reservation::with('user','space')->whereBetween('day', [$start, $end])->where('space_id','=',$spaceId)->paginate(50)->appends(request()->query());
        //dd(DB::getQueryLog()); 
        return $query;
    }
    /**
     * Get all reservation in certain period and user id. That's usefull to check conflicts too.
     * @param  [date] $start
     * @param  [date] $end
     * @param  [integer] $userId
     * @return [Reservation::class] $query
     */
    public static function getByUser($userId, $start, $end){
        $query = Reservation::with('user','space')->whereBetween('day', [$start, $end])->where('user_id','=',$userId)->paginate(50)->appends(request()->query());
        return $query;
    }

}
