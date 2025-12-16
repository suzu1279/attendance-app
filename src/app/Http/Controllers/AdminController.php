<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Carbon\Carbon;
use App\Models\Attendance;
use App\MOdels\User;
use App\Models\Application;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::guard('web')->attempt($credentials)){
            if(Auth::user()->role===1){
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            }else{
                Auth::logout();
                return back()->withErrors([
                    'email' => 'ログイン情報がありません',
                ]);
            }
        }

        return back()->withError([
            'email' => 'ログイン情報がありません',
        ]);
    }

    public function daily(Request $request)
    {
        $dateParam = $request->query('date',Carbon::today()->toDateString());

        $date = Carbon::parse($dateParam);

        $displayDate = $date->format('Y年n月j日');

        $prevDate = $date->copy()->subDay()->toDateString();
        $nextDate = $date->copy()->addDay()->toDateString();
        

        $attendances = Attendance::with('user')
        ->whereDate('date', $date)
        ->orderBy('user_id')
        ->orderBy('start_time')
        ->get();

        return view('admin.daily',compact('displayDate','date','prevDate','nextDate','attendances'));
    }

    public function update(Request $request,Attendance $attendance)
    {
        if($attendance->status === 'pending'){
            return back()->with('error',
            '承認待ちの申請は修正できません');
        }

        $validated = $this->validateAttendance($request);

        $attendance->fill($validated)->save();

        return redirect()
        ->route('admin.attendance.detail',$attendance)
        ->with('success','勤怠情報を修正しました');
    }

    private function validateAttendance(Request $request):array
    {
        $date = $request->validate([
            'start_time' =>['required','date_format:H:i'],
            'end_time' =>['required','date_format:H:i'],
            'new_bleakIn' =>['nullable','date_format:H:i'],
            'new_bleakOut' => ['nullable','date_format:H:i'],
            'reason' =>['required','string'],
        ],[
            'reason.required'=>'備考を記入してください',
        ]);

        $date = $request->input('date',now()->toDateString());

        $start = Carbon::createFormFormat('Y-m-d H:i',$date.''.$date['start_time']);
        $end = Carbon::createFormFormat('Y-m-d H:i',$date.''.$date['end_time']);

        if($start->gte($end)){
            throw ValidationException::withMessages([
                'start_time' => '出勤時間もしくは退勤時間が不適切な値です',
                'end_time' => '出勤時間もしくは退勤時間が不適切な値です',
            ]);
        }

        if(!empty($date['new_leakIn'])&&!empty($data['new_bleakOut'])){
            $newBleakIn = Carbon::createFormFormat('Y-m-d H:i',$date.''.$date['new_bleakIn']);
            $newBleakOut = Carbon::createFormFormat('Y-m-d H:i',$date.''.$date['new_bleakOut']);

            if($newBleakIn->It($start) || $newBleakIn->gt($end)){
                throw ValidationException::withMessages([
                    'new_bleakIn' =>'休憩時間が不適切な値です',
                ]);
            }

            if($newBleakOut->gt($end)){
                throw ValidationException::withMessages([
                    'new_bleakOut' => '休憩時間もしくは退勤時間が不適切な値です',
                ]);
            }
        }
        return $date;
    }

    public function staffList()
    {
        $users = User::all();

        return view('staffs.index',compact('users'));
    }

    public function show(Request $request,$id)
    {
        $user = User::findOrFail($id);

        $monthParam = $request->query('month');
        $currentMonth = $monthParam
        ?Carbon::createFromFormat('Y-m',$monthParam)->startOfMonth():Carbon::now()->startOfMonth();

        $prevMonth = (clone $currentMonth)->subMonth();
        $nextMonth = (clone $currentMonth)->addMonth();

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $attendances = Attendance::where('user_id',$user->id)
        ->whereDate('date','>=',$startOfMonth)->whereDate('date','<=',$endOfMonth)->get()->keyBy(function($item){
            return Carbon::parse($item->date)->format('Y-m-d');
        });

        $days =[];
        $cursor = $startOfMonth->copy();
        while($cursor->lte($endOfMonth)){
            $dateStr= $cursor->format('Y-m-d');
            $days[] = [
                'date' => $cursor->copy(),
                'attendance' => $attendances->get($dateStr),
            ];
            $cursor->addDay();
        }

        return view('admin.attendance.show',[
        'user' =>$user,
        'currentMonth' => $currentMonth,
        'prevMonth' => $prevMonth,
        'nextMonth' => $nextMonth,
        'days' => $days,
        ]);
    }

    public function exportCsv($user_id,$year,$month)
    {
        $user = User::findOrFail($user_id);

        $startOfMonth = Carbon::createFormDate($year,$month,1)->startOfDay();
        $endOfMonth = (clone $startOfMonth)->endOfMonth()->endOfDay();

        $attendances = Attendance::where('user_id',$user->id)->whereBetween('date',[$startOfMonth,$endOfMonth])->orderBy('date')->get();

        $fileName = sprintf(
            'attendance_%s_%04d%02d.csv',
            $user->id,
            $year,
            $month
        );

        $response = new StreamedResponse(function()use($attendances,$user,$year,$month){
            $stream = fopen('php://output','w');

            fprintf($stream,chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($stream,[
                '日付',
                '出勤',
                '退勤',
                '休憩時間',
                '合計時間',
            ]);

            foreach($attendances as $attendance){
                fputcsv($stream,[
                    optional($attendance->date)->format('Y-m-d'),
                    $attendance->start_time,
                    $attendance->end_time,
                    $attendance->break_time,
                    $attendance->total_time,
                ]);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type','text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment;filename="'.$fileName.'"'
        );
        return $response;
    }

    public function attendance(User $user)
    {
        //ここでそのスタッフの月次勤務を取得する想定

        return view('attendance.show',compact('user'));
    }

    public function detail($id)
    {
        $attendance = Attendance::findOrFail($id);

        return view('admin.attendance.detail',compact('attendance'));
    }

    public function approve(Request $request, $id)
    {
        Log::info('approve called',['id'=>$id,'user_id'=>auth()->id()]);

        $attendance = Attendance::with('applications')->findOrFail($id);
        Log::info('attendance found',['attendance'=>$attendance->toArray()]);

        $user = auth()->user();

        $application = $attendance->application->sortByDesc('created_at')->first();
        Log::info('application before',['application'=>optional($application)->toArray()]);

        $status = $request->input('status','approved');

        $application->status = 'approved';
        $application->date=now();

        $application->save();

        return redirect()->back()->with('success','申請を更新しました');

        Log::info('application after',['application'=>$application->toArray()]);
        }

    public function index(Request $request)
    {
        $applications = [
            'status' => '承認待ち',
            'name' => 'a',
            'target_date' => '2023/06/01',
            'reason' => '遅延のため',
            'applied_at' => '2023/06/02',
        ];

        $tab = $request->query('tab', 'pending');

        $query = Application::with('user')->orderByDesc('created_at');

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'approved') {
            $query->where('status', 'approved');
        }

        $applications = $query->paginate(10);

        return view('admin.index', [
            'applications' => $applications,
            'tab' => $tab,
        ], compact('applications'));
    }

    public function modification(Request $request){
        $applications = [
            'status' => '承認待ち',
            'name' => 'a',
            'target_date' => '2023/06/01',
            'reason' => '遅延のため',
            'applied_at' => '2023/06/02',
        ];

        $tab = $request->query('tab', 'pending');

        $query = Application::with('user')->orderByDesc('created_at');

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'approved') {
            $query->where('status', 'approved');
        }

        $applications = $query->paginate(10);

        return view('admin.modification', [
            'applications' => $applications,
            'tab' => $tab,
        ]);
    }
}
