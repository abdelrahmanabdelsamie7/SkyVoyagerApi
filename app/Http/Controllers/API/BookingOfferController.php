<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\{BookingOffer, FlightSchedule};
use App\traits\ResponseJsonTrait;
use Illuminate\Http\Request;
class BookingOfferController extends Controller
{
    use ResponseJsonTrait;

    public function __construct()
    {
        $this->middleware('auth:admins')->only('index');
    }
    public function index()
    {
        $bookings = BookingOffer::with(['user', 'schedule.offer'])->get()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'user' => [
                    'id' => $booking->user->id,
                    'name' => $booking->user->name,
                    'email' => $booking->user->email,
                ],
                'offer' => [
                    'id' => $booking->schedule->offer->id,
                    'title' => $booking->schedule->offer->title,
                    'image_cover' => $booking->schedule->offer->image_cover,
                    'from_date' => $booking->schedule->offer->from_date,
                    'to_date' => $booking->schedule->offer->to_date,
                    'city' => $booking->schedule->offer->city,
                    'price_per_ticket' => $booking->schedule->offer->price_per_ticket,
                    'description' => $booking->schedule->offer->description,
                    'terms_and_conditions' => $booking->schedule->offer->terms_and_conditions,
                ],
                'schedule' => [
                    'id' => $booking->schedule->id,
                    'departure_city' => $booking->schedule->departure_city,
                    'departure_time' => $booking->schedule->departure_time,
                    'arrival_city' => $booking->schedule->arrival_city,
                    'arrival_time' => $booking->schedule->arrival_time,
                    'price_multiplier' => $booking->schedule->price_multiplier,
                    'calculated_price' => $booking->num_of_tickets * $booking->schedule->offer->price_per_ticket,
                ],
                'num_of_tickets' => $booking->num_of_tickets,
                'total_price' => $booking->num_of_tickets * $booking->schedule->offer->price_per_ticket,
            ];
        });

        return $this->sendSuccess('Booking Offers Retrieved Successfully', $bookings);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'schedule_id' => 'required|uuid|exists:flight_schedules,id',
            'num_of_tickets' => 'required|integer|min:1',
        ]);
        $schedule = FlightSchedule::with('offer')->findOrFail($validatedData['schedule_id']);
        if ($validatedData['num_of_tickets'] > $schedule->offer->num_of_tickets) {
            return $this->sendError('Not enough available tickets.', 400);
        }
        $booking = BookingOffer::create(array_merge($validatedData, ['user_id' => auth('api')->id()]));
        $schedule->offer->num_of_tickets -= $validatedData['num_of_tickets'];
        $schedule->offer->save();
        return $this->sendSuccess('Booking Offer Added Successfully', $booking, 201);
    }
    public function destroy($id)
    {
        $booking = BookingOffer::where('id', $id)->where('user_id', auth('api')->id())->first();
        if (!$booking) {
            return $this->sendError('Booking not found or unauthorized.', 404);
        }
        $schedule = $booking->schedule;
        $schedule->offer->available_tickets += $booking->num_of_tickets;
        $schedule->offer->save();
        $booking->delete();
        return $this->sendSuccess('Booking canceled successfully.', null, 200);
    }
}