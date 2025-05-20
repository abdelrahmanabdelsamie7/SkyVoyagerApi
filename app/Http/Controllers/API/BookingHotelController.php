<?php
namespace App\Http\Controllers\API;
use App\Models\{BookingHotel, Hotel};
use Illuminate\Http\Request;
use App\traits\ResponseJsonTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class BookingHotelController extends Controller
{
    use ResponseJsonTrait;

    public function __construct()
    {
        $this->middleware('auth:admins')->only('index');
    }
    public function index()
    {
        $bookings = BookingHotel::with(['user', 'hotel'])->get()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'user_id' => $booking->user_id,
                'hotel_id' => $booking->hotel_id,
                'num_of_beds' => $booking->num_of_beds,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at,
                'number_of_nights' => $booking->number_of_nights,
                'total_price' => $booking->total_price,
                'user' => $booking->user,
                'hotel' => $booking->hotel,
            ];
        });
        return $this->sendSuccess('Booking Hotels Retrieved Successfully', $bookings);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'hotel_id' => 'required|uuid|exists:hotels,id',
            'num_of_beds' => 'required|integer|min:1',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);
        $userId = auth('api')->id();
        if (!$userId) {
            return $this->sendError('Unauthorized. Please log in.', 401);
        }
        try {
            DB::beginTransaction();
            $hotel = Hotel::lockForUpdate()->findOrFail($validatedData['hotel_id']);
            if ($validatedData['num_of_beds'] > $hotel->num_of_beds) {
                throw ValidationException::withMessages(['num_of_beds' => 'Not enough available beds.']);
            }
            $booking = BookingHotel::create(array_merge($validatedData, ['user_id' => $userId]));
            $hotel->decrement('num_of_beds', $validatedData['num_of_beds']);
            DB::commit();
            return $this->sendSuccess('Booking Hotel Added Successfully', $booking, 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->sendError($e->errors(), 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An unexpected error occurred.', 500);
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $booking = BookingHotel::where('id', $id)->where('user_id', auth('api')->id())->first();

            if (!$booking) {
                return $this->sendError('Booking not found or unauthorized.', 404);
            }
            $hotel = $booking->hotel;
            $hotel->increment('num_of_beds', $booking->num_of_beds);
            $booking->delete();
            DB::commit();
            return $this->sendSuccess('Booking canceled successfully.', null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An unexpected error occurred.', 500);
        }
    }
}
