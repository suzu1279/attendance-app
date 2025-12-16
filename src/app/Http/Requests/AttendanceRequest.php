<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_clockIn' =>['required','date_format:H:i'],
            'new_clockOut' =>['required','date_format:H:i','after:clock_in'],
            'new_bleakIn' => ['nullable','date_format:H:i'],
            'new_bleakOut' =>['nullable','date_format:H:i','after:break_start','before_or_equal:clock_out'],
            'vacation_end'=>['nullable','date_format:H:i','before_or_equal:clock_out'],
            'reason'=>['required','string'],
        ];
    }

    public function messages()
    {
        return [
            'new_clockOut.after'=>'出勤時間もしくは退勤時間が不適切な値です',
            'new_bleakOut.after' =>'休憩時間が不適切な値です',
            'new_bleakOut.before_or_equal'=>'休憩時間もしくは退勤時間が不適切な値です',
            'reason.required' =>'備考を記入してください',
        ];
    }
}
