<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Upload;
use Illuminate\Http\Request;

class PhotoController extends Controller
{

    public function index()
    {
        return Upload::latest()->paginate(1);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'photo' => 'required'
        ]);

        if($request->photo){

            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);
           
        }

        Upload::create($request->all());

        return ['message' => 'Success'];

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $upload = Upload::find($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'photo' => 'required'
        ]);

        $currentPhoto = $upload->photo;

        if($request->photo != $currentPhoto){

            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;

            if(file_exists($userPhoto)){

                @unlink($userPhoto);
                
            }
           
        }

        $upload->update($request->all());

        return ['message' => 'Success'];
    }

    public function destroy($id)
    {
        $upload = Upload::findOrFail($id);

        $upload->delete();

        $currentPhoto = $upload->photo;

        $userPhoto = public_path('img/profile/').$currentPhoto;

        if(file_exists($userPhoto)) {

            @unlink($userPhoto);
                
        }

        return [
         'message' => 'Photo deleted successfully'
        ];
    }
}
