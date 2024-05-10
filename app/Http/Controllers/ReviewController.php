<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Requests\Review\ReviewCreateSelfRequest;
use App\Http\Requests\Review\ReviewEditRequest;
use App\Http\Requests\Review\ReviewFiltersRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Http\Resources\Review\ReviewSelfResource;
use App\Models\Review;
use App\Models\Room;

class ReviewController extends Controller
{
    public function createSelf(ReviewCreateSelfRequest $request, int $roomId)
    {
        $user = $request->user();

        $room = Room::find($roomId);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        $reviewExist = Review
        ::where('user_id', $user->id)
        ->where('room_id', $room->id)
        ->count();
        if ($reviewExist)
            throw new ApiException(409, 'You already reviewed this room');

        $review = Review::create([
            ...$request->validated(),
            'user_id' => $user->id,
            'room_id' => $room->id,
        ]);
        return response(ReviewSelfResource::make($review), 201);
    }
    public function deleteSelf(int $roomId)
    {
        $user = request()->user();

        $room = Room::find($roomId);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        $review = Review
        ::where('room_id', $room->id)
        ->where('user_id', $user->id)
        ->first();
        if (!$review)
            throw new ApiException(404, 'Review not found');

        $review->delete();
        return response(null, 204);
    }

    public function showAll(ReviewFiltersRequest $request)
    {
        $query = Review
            ::with(['room', 'user'])
            ->orderBy('is_moderated');

        if ($request->users)     $query->whereIn('user_id'     , $request->users);
        if ($request->rooms)     $query->whereIn('room_id'     , $request->rooms);
        if ($request->moderated) $query->where  ('is_moderated', $request->moderated);

        return response(['reviews' => ReviewResource::collection($query->get())]);
    }
    public function show(int $id)
    {
        $review = Review::with(['room', 'user'])->find($id);

        if (!$review)
            throw new ApiException(404, 'Review not found');

        return response(ReviewResource::make($review));
    }
    public function create(ReviewCreateRequest $request)
    {
        if (Review
            ::where('user_id', $request->user_id)
            ->where('room_id', $request->room_id)
            ->exists()
        ) throw new ApiException(409, 'User already reviewed this room');

        $review = Review::create($request->validated());

        return response(ReviewResource::make($review), 201);
    }
    public function edit(ReviewEditRequest $request, int $id)
    {
        $review = Review::find($id);
        if (!$review)
            throw new ApiException(404, 'Review not found');

        $review->update($request->validated());
        return response([$review, $request->validated()], 200);
    }
    public function delete(int $id)
    {
        $review = Review::find($id);
        if (!$review)
            throw new ApiException(404, 'Review not found');

        $review->delete();
        return response(null, 204);
    }
}
