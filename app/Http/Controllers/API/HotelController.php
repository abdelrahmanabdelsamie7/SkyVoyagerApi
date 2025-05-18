<?php
namespace App\Http\Controllers\API;
use App\Models\Hotel;
use App\traits\ResponseJsonTrait;
use App\Http\Requests\HotelRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class HotelController extends Controller
{
    use ResponseJsonTrait;
    public function __construct()
    {
        $this->middleware('auth:admins')->only(['store', 'update', 'destroy']);
    }
    public function index()
    {
        $hotels = Hotel::all();
        return $this->sendSuccess('Hotels Retrieved Successfully!', $hotels);
    }
    public function show(string $id)
    {
        $hotel = Hotel::findOrFail($id);
        return $this->sendSuccess('Specific Hotel Retrieved Successfully!', $hotel);
    }
    public function store(HotelRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image_cover')) {
            $data['image_cover'] = $this->uploadImage($request->file('image_cover'));
        }
        $hotel = Hotel::create($data);
        return $this->sendSuccess('Hotel Added Successfully', $hotel, 201);
    }
    public function update(HotelRequest $request, string $id)
    {
        $hotel = Hotel::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image_cover')) {
            $oldImagePath = public_path('uploads/hotels/' . basename($hotel->image_cover));
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $data['image_cover'] = $this->uploadImage($request->file('image_cover'));
        }
        $hotel->update($data);
        return $this->sendSuccess('Hotel Updated Successfully', $hotel, 200);
    }
    private function uploadImage($image)
    {
        if ($image) {
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/hotels'), $imageName);
            return asset('uploads/hotels/' . $imageName);
        }
        return null;
    }
    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        if ($hotel->image && !str_contains($hotel->image, 'default.jpg')) {
            $imageName = basename($hotel->image);
            $imagePath = public_path("uploads/hotels/" . $imageName);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $hotel->delete();
        return $this->sendSuccess('Hotel Deleted Successfully');
    }
}
