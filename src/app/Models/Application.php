<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'date',
        'reason',
        'status',
        'new_clockIn',
        'new_clockOut',
        'new_bleakIn',
        'new_bleakOut',
        'new_bleakIn2',
        'new_bleakOut2'
    ];

    protected $casts = [
        'target_date' => 'date',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class,'attendance_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute():string{
        return match($this->status){
            'pending' =>'承認待ち',
            'approved' => '承認済み',
            'rejected' => '否認',
        };
    }
}
