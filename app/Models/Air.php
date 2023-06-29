<?php

namespace App\Models;

use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Air extends Model
{
    use HasFactory, HttpResponses;  use HasFactory, HttpResponses;

    protected $fillable = [
        'temperature',
        'humidity',
        'power',
    ];

    protected $hidden = [
        'updated_at', 'id'
    ];

    public function create_model($request)
    {
        $request->validated($request->all());

        $air = Air::create([
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'power' => $request->power,
        ]);
        return $this->succes([
            'air' => $air
        ], 'Air created successfully');
    }

    public function create_model_test($temp)
    {
        $air = Air::create([
            'temperature' => $temp,
            'humidity' => 'teste1',
            'power' => 0
        ]);
        return $this->succes([
            'air' => $air
        ], 'Air created successfully');
    }

    public function list_airs($flag = 400)
    {
        $last = Air::whereDate('created_at', now())->get()->last();
        $result = null;
        $response = null;

        if($last){
            $response = [
                'current_status' => $last->power,
                'temperature' => $last->temperature,
                'humidity' => $last->humidity,
                'time_off' => $result,
                'flag' => 0
            ];
        }

        if($last->power == 0){
            $last_on = Air::whereDate('created_at', now())->select('created_at', 'power')->where('power', '=', 1)->get()->last();

            if($last_on){
                $date1 = Carbon::parse($last->created_at);
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
