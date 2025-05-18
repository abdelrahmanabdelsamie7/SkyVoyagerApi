<?php
namespace App\Http\Controllers\API;
use App\Models\OfferImage;
use App\traits\ResponseJsonTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\OfferImageRequest;
class OfferImageController extends Controller
{
    use ResponseJsonTrait;
    public function __construct()
    {
        $this->middleware('auth:admins')->only(['store', 'destroy']);
    }
    public function store(OfferImageRequest $request)
    {
        $data = $request->validated();
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $this->uploadImage($image);
                $offerImage = OfferImage::create([
                    'offer_id' => $data['offer_id'],
                    'image' => $imagePath,
                ]);
                $images[] = $offerImage->fresh();
            }
        }
        return $this->sendSuccess('Images Added To Offer Successfully', $images, 201);
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
        $category = OfferImage::findOrFail($id);
        if ($category->image && !str_contains($category->image, 'default.jpg')) {
            $imageName = basename($category->image);
            $imagePath = public_path("uploads/offers/" . $imageName);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $category->delete();
        return $this->sendSuccess('Image Of Offer Deleted Successfully!');
    }
}