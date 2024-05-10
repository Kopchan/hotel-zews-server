<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Service\ServiceItemCreateRequest;
use App\Http\Requests\Service\ServiceItemEditRequest;
use App\Models\Service;
use App\Models\ServiceItem;

class ServiceItemController extends Controller
{
    public function create(ServiceItemCreateRequest $request, $serviceId)
    {
        $service = Service::find($serviceId);
        if (!$service)
            throw new ApiException(404, 'Service not found');

        ServiceItem::create([
            ...$request->validated(),
            'service_id' => $serviceId,
        ]);
        return response(null, 201);
    }
    public function edit(ServiceItemEditRequest $request, $serviceId, $itemId)
    {
        $serviceItem = ServiceItem::find($itemId);
        if (!$serviceItem)
            throw new ApiException(404, 'Service item not found');

        $serviceItem->update($request->validated());
        return response(null, 204);
    }
    public function delete($serviceId, $itemId)
    {
        $serviceItem = ServiceItem::find($itemId);
        if (!$serviceItem)
            throw new ApiException(404, 'Service item not found');

        $serviceItem->delete();
        return response(null, 204);
    }
}
