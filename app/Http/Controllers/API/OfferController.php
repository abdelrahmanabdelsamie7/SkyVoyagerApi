<?php
namespace App\Http\Controllers\API;
use App\Models\Offer;
use App\traits\ResponseJsonTrait;
use App\Http\Requests\OfferRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class OfferController extends Controller
{
    use ResponseJsonTrait;
    public function index()
    {
        $offers = Offer::all();
        return $this->sendSuccess('Offers Retrieved Successfully!', $offers);
    }
    public function show(string $id)
    {
        $offer = Offer::with('flightSchedules')->findOrFail($id);
        return $this->sendSuccess('Specific Offer Retrieved Successfully!', $offer);
    }
    public function store(OfferRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image_cover')) {
            $data['image_cover'] = $this->uploadImage($request->file('image_cover'));
        }
        $offer = Offer::create($data);
        return $this->sendSuccess('Offer Added Successfully', $offer, 201);
    }
    public function update(OfferRequest $request, string $id)
    {
        $offer = Offer::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image_cover')) {
            $oldImagePath = public_path('uploads/offers/' . basename($offer->image_cover));
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $data['image_cover'] = $this->uploadImage($request->file('image_cover'));
        }
        $offer->update($data);
        return $this->sendSuccess('Offer Updated Successfully', $offer, 200);
    }
    private function uploadImage($image)
    {
        if ($image) {
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/offers'), $imageName);
            return asset('uploads/offers/' . $imageName);
        }
        return null;
    }
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        if ($offer->image && !str_contains($offer->image, 'default.jpg')) {
            $imageName = basename($offer->image);
            $imagePath = public_path("uploads/offers/" . $imageName);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $offer->delete();
        return $this->sendSuccess('Offer Deleted Successfully');
    }
}