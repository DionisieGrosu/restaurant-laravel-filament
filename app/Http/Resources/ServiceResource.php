<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ServiceResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title ? $this->title : '',
            'description' => $this->description ? $this->description : '',
            'short_description' => $this->short_description ? $this->short_description : '',
            'seo_title' => $this->seo_title ? $this->seo_title : '',
            'seo_desc' => $this->seo_desc ? $this->seo_desc : '',
            'seo_keywords' => $this->seo_keywords ? $this->seo_keywords : '',
            'img' => $this->img ? (Storage::disk('public')->exists($this->img) ? asset('storage/' . $this->img) : '') : '',
        ];
    }
}
