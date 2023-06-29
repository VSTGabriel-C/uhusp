<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAirRequest;
use App\Models\Air;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class AirController extends Controller
{
    use HttpResponses;

    public function create(CreateAirRequest $request)
    {
        $air = (new Air)->create_model($request);

        return $air;
    }

    public function show()
    {
        $response = Air::whereDate('created_at', now())->simplePaginate(50);

        if (!$response) {
            return $this->error('', 'Empty list', 401);
        }

        return $this->succes([
            'air' => $response
        ], 'List air successfully');
    }

    public function update(Request $request, $id)
    {
        $air = (new Air)->update_model($request, $id);

        return $air;
    }

    public function list_airs()
    {
        $air = (new Air)->list_airs();

        return $air;
    }

    public function delete($id)
    {
        $air = (new Air)->delete_model($id);

        return $air;
    }
}
