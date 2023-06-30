<?php

namespace App\Models;

use App\Exports\AirExport;
use App\Exports\AirExportTest;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Air extends Model
{
    use HasFactory, HttpResponses;  use HasFactory, HttpResponses;

    protected $fillable = [
        'temperature',
        'humidity',
        'power',
        'days'
    ];

    protected $hidden = [
        'updated_at', 'id'
    ];

    public function create_model($request)
    {
        $request->validated($request->all());

        $last_off = Air::where('power', 0)->get()->last();
        $days = 0;

        if($last_off){
            $last_on = Air::where([['created_at', '>', $last_off->created_at], ['power','=',1]])->get()->first();
            if($last_on){
                $day_off = Carbon::parse($last_on->created_at);
                $days = Carbon::now()->diffInDays($day_off);
            }
        }

        if($request->power == 0) $days = 0;

        $air = Air::create([
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'power' => $request->power,
            'days' => $days
        ]);
        return $this->succes([
            'air' => $air
        ], 'Air created successfully');
    }

    public function show($request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $quant = $request->quant;

        if(!$quant) $quant = 20;

        if($date_start){
            if(!$date_end){
                $response = Air::whereDate('created_at',$request->date_start)->orderBy('created_at', 'desc')->simplePaginate($quant)->toArray();
            }
            else{
                $response = Air::whereDate('created_at','>=',$request->date_start)
                ->whereDate('created_at','<=',$request->date_end)->orderBy('created_at', 'desc')
                ->simplePaginate($quant)->toArray();
            }
        }

        else {
            $response = Air::whereDate('created_at', now())->orderBy('created_at', 'desc')->simplePaginate($quant)->toArray();
        }

        if (!$response) {
            return $this->error('', 'Empty list', 401);
        }

        return $response;

    }
    public function export_model($request)
    {
        $response = $this->show($request);

        return Excel::download(new AirExport($response['data']), 'air.xlsx');

    }

    public function list_airs($flag = 100)
    {
        $last = Air::whereDate('created_at', now())->get()->last();
        $result = null;
        $response = [];

        if($last){
            $response = [
                'current_status' => $last->power,
                'temperature' => $last->temperature,
                'humidity' => $last->humidity,
                'days_on' => $last->days,
                'time_off' => $result,
                'flag' => 0
            ];

            if($last->power == 0){
                $last_on = Air::select('created_at', 'power')->where('power', '=', 1)->get()->last();

                if($last_on){
                    $date1 = Carbon::now();
                    $date2 = Carbon::parse($last_on->created_at);

                    $result = $date1->diffInMinutes($date2);

                    $response['time_off'] = $result;

                    if($flag < $result){
                        $response['flag'] = 1;
                    }
                }
                else{
                    $response['time_off'] = "undetermined";
                    $response['flag'] = 1;
                }
            }
        }

        return $response;
    }

    public function update_model($request, $id)
    {
        $air = Air::where('id', $id)->first();

        if (!$air) {
            return $this->error('', 'Id do not match', 401);
        }

        $air->update($request->all());

        return $this->succes([
            'equipe' => $air
        ], 'Air updated successfully');
    }

    public function delete_model($id)
    {
        $air = Air::where('id', $id)->first();

        if (!$air) {
            return $this->error('', 'Id do not match', 401);
        }

        $air->delete();

        return $this->succes('', 'Air deleted successfully');
    }


}
