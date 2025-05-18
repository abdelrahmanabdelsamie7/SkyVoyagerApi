<?php
namespace App\Http\Controllers\API;
use App\Models\PopularPlace;
use App\traits\ResponseJsonTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\PopularPlaceRequest;

class PopularPlaceController extends Controller
{
    use ResponseJsonTrait;
    public function __construct()
    {
        $this->middleware('auth:admins')->only(['store', 'update', 'destroy']);
    }
    public function index()
    {
        $popular_places = PopularPlace::all();
        return $this->sendSuccess('Popular Places Retrieved Successfully!', $popular_places);
    }
    public function show(string $id)
    {
        $popular_place = PopularPlace::findOrFail($id);
        return $this->sendSuccess('Specific Popular Place Retrieved Successfully!', $popular_place);
    }
    public function store(PopularPlaceRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image_cover')) {
            $data['image_cover'] = $this->uploadImage($request->file('image_cover'));
        }
        $popular_place = PopularPlace::create($data);
        return $this->sendSuccess('Popular Place Added Successfully', $popular_place, 201);
    }
    public function update(PopularPlaceRequest $request, string $id)
    {
        $popular_place = PopularPlace::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image_cover')) {
            $oldImagePath = public_path('uploads/popularPlaces/' . basename($popular_place->image_cover));
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $data['image_cover'] = $this->uploadImage($request->file('image_cover'));
        }
        $popular_place->update($data);
        return $this->sendSuccess('Popular Place Updated Successfully', $popular_place, 200);
    }
    private function uploadImage($image)
    {
        if ($image) {
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/popularPlaces'), $imageName);
            return asset('uploads/popularPlaces/' . $imageName);
        }
        return null;
    }
    public function destroy($id)
    {
        $popular_place = PopularPlace::findOrFail($id);
        if ($popular_place->image && !str_contains($popular_place->image, 'default.jpg')) {
            $imageName = basename($popular_place->image);
            $imagePath = public_path("uploads/popularPlaces/" . $imageName);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $popular_place->delete();
        return $this->sendSuccess('Popular Place Deleted Successfully');
    }
}
