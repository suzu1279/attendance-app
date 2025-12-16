<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab','pending');

        $query = Application::with('user')->orderByDesc('created_at');

        if($tab === 'pending'){
            $query->where('status','pending');
        }elseif($tab === 'approved'){
            $query->where('status','approved');
        }

        //$applications = $query->paginate(10);

        return view('application.index',compact('applications','tab'));
    }

    public function application(Request $request){
    }

    public function store(Request $request,$id)
    {
        $validated = $request->validate([
            'date' =>['required','date'],
            'new_clockIn' =>['nullable','date_format:H:i'],
            'new_clockOut' =>['nullable','date_format:H:i'],
            'reason' => ['nullable','string'],
        ]);

        $validated['user_id']=auth()->id();
        $validated['attendance_id']=$id;

        Application::create($validated);

        return redirect()->route('attendance.detail',$id)->with('success','申請を登録しました');
    }

    
}
