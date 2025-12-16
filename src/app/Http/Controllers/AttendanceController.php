<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;



class AttendanceController extends Controller
{
    public function attendance()
    {
        setlocale(LC_TIME,'ja_JP.UTF-8');
        $currentDateTime=Carbon::now();

        $formattedDate = $currentDateTime->translatedFormat('Y年m月d日(l)');
        $formattedTime = $currentDateTime->format('H:i');

        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if(! $attendance){
            $user->attendance_status=0;
            $user -> save();
        }

        switch ($user->attendance_status) {
            case 0:
                $attendanceLabel = '勤務外';
                break;
            case 1:
                $attendanceLabel = '出勤中';
                break;
            case 2:
                $attendanceLabel = '休憩中';
                break;
            case 3:
                $attendanceLabel = '退勤済';
    }
        return view('attendance',compact('attendanceLabel'),[
            'formattedDate' => $formattedDate,
            'formattedTime' => $formattedTime,
        ]);
    }

    public function startWork(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $startTime = Carbon::now()->format('H:i');
        
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'start_time' => $startTime
            ],
        );
        if(in_array($user->attendance_status, [1,3])){
            return redirect()->back()->with('error','すでに出勤中、または退勤済みです。');
        }

        $user->attendance_status = 1;
        $user->save();

        return redirect()->back()->with('success','出勤しました');
    }

    public function endWork(Request $request)
    {
        $user = Auth()->user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)->where('date',$today)->first();

        if(!$attendance){
            return redirect()->back()->with('error','出勤記録がありません');
        }

        if($user->attendance_status === 3){
            return redirect()->back()->with('error','すでに退勤済みです');
        }
        $attendance->end_time=now();

        $attendance->save();

        $user->attendance_status = 3;
        $user->save();

        return redirect()->back()->with('success','退勤しました');
    }

    public function startBreak(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)->whereDate('date',$today)->first();

        if(!$attendance){
            return redirect()->back()->with('error','出勤記録がありません');
        }

        if($user->attendance_status !== 1){
            return redirect()->back()->with('error','出勤中のみ休憩に入れます');
        }

            $user->attendance_status = 2;
            $user->save();

            return redirect()->back()->with('success','休憩に入りました');
    }

    public function endBreak(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id',$user->id)->whereDate('date', $today)->first();

        if(!$attendance){
            return redirect()->back()->with('error','出勤記録がありません');
        }

        if($user->attendance_status !== 2){
            return redirect()->back()->with('error','休憩中ではありません');
        }

        $user->attendance_status = 1;
        $user->save();

        return redirect()->back()->with('success','休憩を終了しました');
    }

    public function list($year = null, $month =null)
    {
        $current = Carbon::create($year ?? now()->year, $month ?? now()->month, 1);

        $startOfMonth = $current->copy()->startOfMonth();
        $endOfMonth = $current->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', auth()->id())
        ->whereBetween('date',[$startOfMonth,$endOfMonth])
        ->get()
        ->keyBy(function ($item){
            return $item->date->format('Y-m-d');
        });

        $prev = $current->copy()->subMonth();
        $next = $current->copy()->addMonth();

        $days = [];
        $cursor = $startOfMonth->copy();
        while($cursor->lte($endOfMonth)){
            $dateKey = $cursor->format('Y-m-d');
            $days[] = [
                'carbon' => $cursor->copy(),
                'attendance' => $attendances[$dateKey] ?? null,
            ];
            $cursor->addDay();
        }

        return view('attendance.list',compact('current','prev','next','days'));
    }

    public function show($id)
    {
        $attendance = Attendance::with('breakApplications')->find($id);
        $attendance->total_break_minutes;

        $attendance = Attendance::with('breakApplications')->findOrFail($id);

        $totalBreakMinutes = $attendance->total_break_minutes;

        return view('attendance.show',compact('attendance','totalBreakMinutes'));
    }

    protected $appends = ['total_break_minutes'];

    public function getTotalBreakMinutesAttribute()
    {
        $breaks = $this->relationLoaded('breakApplications')
        ? $this->breakApplications
        : $this->breakApplications()->get();

        return $breaks->sum(function($break){
            if($break->new_bleakIn || !$break->new_bleakOut){
                return 0;
            }
            $start = Carbon::parse($break->new_bleakIn);
            $end = Carbon::parse($break->new_bleakOut);

            return $end->diffInMinutes($start);
        });
        
    }

    public function detail($id)
    {
        $attendance =Attendance::with('user','applications')->findOrFail($id);

        return view('attendance.detail',compact('attendance'));
    }

    public function update(Request $request,$id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
        ]);

        $attendance->start_time =$validated['start_time'];
        $attendance->end_time =$validated['end_time']?? $attendance->end_time;

        $attendance->save();

        return redirect()->route('attendance.detail',$attendance->id->with('status','勤怠情報を修正しました'));
    }
}
