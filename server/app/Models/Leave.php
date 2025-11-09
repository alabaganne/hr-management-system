<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *      schema="leave",
 *      title="Leave",
 *      description="Leave object",
 *      
 *      @OA\Property(property="type", type="string", enum={"option 1", "option 2", "option 3"}),
 *      @OA\Property(property="days", type="integer", example=3)
 * )
*/

class Leave extends Model
{
    use HasFactory;

    public $fillable = ['days'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
