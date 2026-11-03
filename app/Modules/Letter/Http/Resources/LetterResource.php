<?php

namespace Modules\Letter\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LetterResource extends JsonResource
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
            'category_id' => $this->category_id,
            'direction' => $this->direction,
            'direction_label' => $this->direction_label,
            'letter_number' => $this->letter_number,
            'subject' => $this->subject,
            'sender' => $this->sender,
            'recipient' => $this->recipient,
            'letter_date' => $this->letter_date?->format('Y-m-d'),
            'received_date' => $this->received_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'code' => $this->category->code,
                'name' => $this->category->name,
            ]),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
