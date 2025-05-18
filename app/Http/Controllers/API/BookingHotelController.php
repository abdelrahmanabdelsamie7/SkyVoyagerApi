<?php
namespace App\Http\Controllers\API;
use App\Models\{BookingHotel,Hotel};
use Illuminate\Http\Request;
use App\traits\ResponseJsonTrait;
use App\Http\Controllers\Controller;

class BookingHotelController extends Controller
{
    use ResponseJsonTrait ;
    public function __construct()
    {
        $this->middleware('auth:admins')->only('index');
    }
     public function index()
    {
        $bookings = BookingHotel::with(['user', 'hotel'])->get();
        return $this->sendSuccess('Booking Hotels Retreived Successfully ', $bookings) ;
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'hotel_id' => 'required|uuid|exists:hotels,id',
            'num_of_rooms' => 'required|integer|min:1',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);
        $hotel = Hotel::findOrFail($validatedData['hotel_id']);
        if ($validatedData['num_of_rooms'] > $hotel->available_rooms) {
            return $this->sendError('Not enough available rooms.', 400);
        }
        $booking = BookingHotel::create(array_merge($validatedData, ['user_id' => auth('api')->id()]));
        $hotel->available_rooms -= $validatedData['num_of_rooms'];
        $hotel->save();
        return $this->sendSuccess('Booking Hotel Added Successfully', $booking, 201);
    }
    public function destroy($id)
    {
        $booking = BookingHotel::where('id', $id)->where('user_id', auth('api')->id())->first();
        if (!$booking) {
            return $this->sendError('Booking not found or unauthorized.', 404);
        }
        $hotel = $booking->hotel;
        $hotel->available_rooms += $booking->num_of_rooms;
        $hotel->save();
        $booking->delete();
        return $this->sendSuccess('Booking canceled successfully.', null, 200);
    }
}
