<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Service\ServiceCreateRequest;
use App\Http\Resources\Service\ServiceResource;
use App\Models\Photo;
use App\Models\Service;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function showAll()
    {
        $services = Service::with('items')->get();
        return response(['services' => ServiceResource::collection($services)]);
    }
    public function show($id)
    {
        $service = Service::with('items')->find($id);
        if (!$service)
            throw new ApiException(404, 'Service not found');

        return response(ServiceResource::make($service));
    }
    public function create(ServiceCreateRequest $request)
    {
        $service = Service::create($request->validated());

        if ($request->has('photo')) {
            $file = $request->file('photo');
            $fileExt = $file->extension();
            $fileHash = md5(File::get($file->getRealPath()));
            $photoName = "$fileHash.$fileExt";
            $photo = Photo::firstOrCreate(['name' => $photoName]);

            if (!Storage::exists('public' . $photoName))
                $file->storeAs('public', $photoName);

            $service->photo_id = $photo->id;
            $service->save();
        }
        return response(ServiceResource::make($service), 201);
    }
    public function edit(ServiceCreateRequest $request, $id)
    {
        $service = Service::with('items')->find($id);
        if (!$service)
            throw new ApiException(404, 'Service not found');

        $service->update($request->validated());

        if ($request->has('photo')) {
            $file = $request->file('photo');
            $fileExt = $file->extension();
            $fileHash = md5(File::get($file->getRealPath()));
            $photoName = "$fileHash.$fileExt";
            $photo = Photo::firstOrCreate(['name' => $photoName]);

            if (!Storage::exists('public' . $photoName))
                $file->storeAs('public', $photoName);

            $service->photo_id = $photo->id;
            $service->save();
        }
        return response(ServiceResource::make($service));
    }
}
