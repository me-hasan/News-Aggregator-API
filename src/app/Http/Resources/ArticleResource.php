<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'summary'      => $this->summary,
            'content'      => $this->content,
            'url'          => $this->url,
            'image_url'    => $this->image_url,
            'source'       => $this->source,
            'author'       => $this->author,
            'category'     => $this->category,
            'published_at' => $this->published_at,
        ];
    }
}
