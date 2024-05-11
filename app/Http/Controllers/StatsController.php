<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Reservation\StatsRequest;
use App\Models\Reservation;

class StatsController extends Controller
{
    public function show(StatsRequest $request)
    {
        $entryDate = $request->start ?? now()->sub(\DateInterval::createFromDateString('30 days'));
        $exitDate  = $request->end   ?? now();
        $query = Reservation::query()
            ->selectRaw(
                'sum(reservations.price) sum, '.
                'count(reservations.id) count'
            )->where(function ($q) use ($entryDate, $exitDate) {
                $q->orWhere(function ($q01) use ($entryDate, $exitDate) {
                    $q01->where('date_entry', '>=', $entryDate)
                        ->where('date_exit' , '<=', $exitDate);
                })->orWhere(function ($q02) use ($entryDate, $exitDate) {
                    $q02->where('date_entry', '<=', $exitDate)
                        ->where('date_exit' , '>=', $entryDate);
                });
            });

        if ($request->types) {
            $query->leftJoin('rooms', 'reservations.room_id', 'rooms.id');
            $query->whereIn('rooms.type_id', $request->types);
        }

        $result = $query->first();
        if (!$result->sum)
            return response(['message' =>
                'There are no reservations for the '.(
                    $request->date_start
                    ? 'specified period'
                    : 'last 30 days'
                )
            ]);

        return response([
            'sum'   => $result->sum,
            'count' => $result->count,
        ]);
    }
}
