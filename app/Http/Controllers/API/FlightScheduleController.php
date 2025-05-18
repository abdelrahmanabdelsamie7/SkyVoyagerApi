<?php
namespace App\Http\Controllers\API;
use App\Http\Requests\FlightScheduleRequest;
use App\Models\FlightSchedule;
use App\traits\ResponseJsonTrait;
use App\Http\Controllers\Controller;

class FlightScheduleController extends Controller
{
    use ResponseJsonTrait;
    public function __construct()
    {
        $this->middleware('auth:admins')->only(['store', 'update', 'destroy']);
    }
    public function index()
    {
        $flight_schedules = FlightSchedule::all();
        return $this->sendSuccess('Flight Schedules Retrieved Successfully!', $flight_schedules);
    }
    public function show(string $id)
    {
        $flight_schedule = FlightSchedule::findOrFail($id);
        return $this->sendSuccess('Specific Flight Schedule Retrieved Successfully!', $flight_schedule);
    }
    public function store(FlightScheduleRequest $request)
    {
        $flight_schedule = FlightSchedule::create($request->validated());
        return $this->sendSuccess('Flight Schedule Added Successfully', $flight_schedule, 201);
    }
    public function update(FlightScheduleRequest $request, string $id)
    {
        $flight_schedule = FlightSchedule::findOrFail($id);
        $data = $request->validated();
        $flight_schedule->update($data);
        return $this->sendSuccess('Flight Schedule Updated Successfully', $flight_schedule, 200);
    }
    public function destroy($id)
    {
        $flight_schedule = FlightSchedule::findOrFail($id);
        $flight_schedule->delete();
        return $this->sendSuccess('Flight Schedule Deleted Successfully');
    }
}