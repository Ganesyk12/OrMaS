<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'activity_id' => $this->activity_id,
            'type' => $this->type,
            'total' => $this->total,
            'transaction_date' => $this->transaction_date?->format('Y-m-d'),
            'status' => $this->status,
            'organization' => $this->whenLoaded('organization', fn () => [
                'id' => $this->organization->id,
                'name' => $this->organization->name,
                'code' => $this->organization->code,
            ]),
            'activity' => $this->whenLoaded('activity', fn () => [
                'id' => $this->activity->id,
                'title' => $this->activity->title,
                'type' => $this->activity->type,
            ]),
            'details' => FinanceDetailResource::collection($this->whenLoaded('details')),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
