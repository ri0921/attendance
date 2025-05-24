<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class StampCorrectionRequest extends FormRequest
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
            'clock_in' => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i', 'after:clock_in'],
            'break_times' => ['nullable', 'array'],
            'break_time.*.break_start' => ['nullable', 'date_format:H:i'],
            'break_time.*.break_end' => ['nullable', 'date_format:H:i'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return[
            'clock_in.required' => '出勤時間を入力してください',
            'clock_in.date_format' => '出勤時間の形式が正しくありません',
            'clock_out.required' => '退勤時間を入力してください',
            'clock_out.date_format' => '退勤時間の形式が正しくありません',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'reason.required' => '備考を記入してください',
            'reason.string' => '備考は文字列で入力してください',
            'reason.max' => '備考は255文字以内で入力してください',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $clock_in = $this->input('clock_in');
            $clock_out = $this->input('clock_out');
            $break_times = $this->input('break_time', []);
            try {
                $clock_in_time = Carbon::createFromFormat('H:i', $clock_in);
                $clock_out_time = Carbon::createFromFormat('H:i', $clock_out);
            } catch (\Exception $e) {
                return;
            }

            foreach ($break_times as $i => $break_time) {
                $start = $break_time['break_start'] ?? null;
                $end = $break_time['break_end'] ?? null;

                if ($start && !$end) {
                    $validator->errors()->add("break_time.$i.break_start", "休憩終了時間を入力してください");
                    continue;
                }
                if (!$start && $end) {
                    $validator->errors()->add("break_time.$i.break_start", "休憩開始時間を入力してください");
                    continue;
                }
                if (!$start && !$end) {
                    continue;
                }
                try {
                    $start_time = Carbon::createFromFormat('H:i', $start);
                    $end_time = Carbon::createFromFormat('H:i', $end);
                } catch (\Exception $e) {
                    $validator->errors()->add("break_time.$i.break_start", "休憩の時間形式が正しくありません。");
                    continue;
                }
                if ($start_time->gte($end_time)) {
                    $validator->errors()->add("break_time.$i.break_start", "休憩時間が不適切な値です");
                }
                if ($start_time->lt($clock_in_time) || $end_time->gt($clock_out_time)) {
                    $validator->errors()->add("break_time.$i.break_start", "休憩時間が勤務時間外です");
                }
                $break_times[$i] = ['break_start' => $start_time, 'break_end' => $end_time];
            }

            foreach ($break_times as $i => $b1) {
                foreach ($break_times as $j => $b2) {
                    if ($i >= $j) continue;
                    if (
                        $b1['break_start']->lt($b2['break_end']) &&
                        $b1['break_end']->gt($b2['break_start'])
                    ) {
                        $validator->errors()->add("break_time.$i.break_start", "休憩時間が重複しています");
                    }
                }
            }
        });
    }
}
