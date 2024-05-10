<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\News\NewsCreateRequest;
use App\Http\Requests\News\NewsEditRequest;
use App\Http\Requests\News\NewsFiltersRequest;
use App\Http\Resources\News\NewsResource;
use App\Models\News;
use App\Models\Photo;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function showAll(NewsFiltersRequest $request)
    {
        $query = News
            ::with('photos')
            ->orderByDesc('created_at');

        if ($request->limit) $query->limit($request->limit);
        if ($request->cut)   $query->select(
            DB::raw('*'),
            DB::raw("SUBSTRING(text, 1, $request->cut) AS text")
        );

        return response(['news' => NewsResource::collection($query->get())]);
    }
    public function show(int $id)
    {
        $news = News::with('photos')->find($id);
        if (!$news)
            throw new ApiException(404, 'News not found');

        return response(NewsResource::make($news));
    }
    public function create(NewsCreateRequest $request)
    {
        $news = News::create($request->validated());
        $response = $news->loadPhotos($request->file('photos'));

        $response['news'] = NewsResource::make($news);

        return response($response, 201);
    }
    public function edit(NewsEditRequest $request, int $id)
    {
        $news = News::find($id);
        if (!$news)
            throw new ApiException(404, 'News not found');

        $news->update($request->validated());
        $response = $news->loadPhotos($request->file('photos'));

        foreach ($request->removePhotos ?? [] as $removePhoto) {
            $photo = Photo::find($removePhoto);
            if ($photo) {
                $photo->news_id = null;
                $photo->save();
            }
        }
        $response['news'] = NewsResource::make($news);

        return response($response);
    }
    public function delete(int $id)
    {
        $news = News::find($id);

        if (!$news)
            throw new ApiException(404, 'News not found');

        $news->delete();
        return response(null, 204);
    }
}
