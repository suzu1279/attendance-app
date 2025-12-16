<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Application;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $dates = [
        'date',
        'start_time',
        'end_time',
    ];

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'total_time',
        'status'
    ];

    protected $casts =[
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected $appends = ['total_break_minutes'];

    public function breakApplications()
    {
        return $this->hasMany(Application::class,'attendance_id');
    }

    public function getTotalBreakMinutesAttribute()
    {

        return $this->breakApplications->sum(function ($break){
            $start = Carbon::parse($break->new_bleakIn);
            $end = Carbon::parse($break->new_bleakOut);
            
            return $end->diffInMinutes($start);

        });
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function applications()
    {
        return $this->hasMany('App\Models\Application','attendance_id','id');
    }

    public function getNextAction()
    {
        if(!$this->start_time){
            return '出勤';
        }
        if(!$this->break_start && !$this->end_time){
            return '休憩入';
        }
        if(!$this->break_start && !$this->end_time){
            return '退勤';
        }
        return 'お疲れ様でした';
    }

    public function getBreakDurationAttribute()
    {
        if ($this->break_minutes === null)return null;
        $h = intdiv($this->break_minutes,60);
        $m = $this->break_minutes % 60;
        return sprintf('%d:%02d',$h,$m);
    }

    public function getTotalDurationAttribute()
    {
        if (!$this->start_time || !$this->end_time) return null;

        $diff = $this->start_time->diffInMinutes($this->end_time);

        if($diff < 0){
            return null;
        }

        $h = intdiv($diff,60);
        $m = $diff % 60;
        return sprintf('%d:%02d', $h,$m);
    }
}
