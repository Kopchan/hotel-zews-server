<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\ApiRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class ServiceItemEditRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'  => [
                'string',
                'max:64',
                Rule::unique('service_items')->where(fn (Builder $query) =>
                    $query->where('service_id', request('id')))
            ],
            'price' => 'string',
        ];
    }
}
