<?php
declare(strict_types=1);

namespace App\DTO;

use App\Enums\PetStatus;

/**
 *
 */
class PetDto
{


    /**
     * @param int|null $id
     * @param string $name
     * @param string|null $status
     * @param array $photoUrls
     * @param CategoryDto|null $category
     * @param array|null $tags
     */
    public function __construct(
        public readonly ?int         $id,
        public readonly string       $name,
        public readonly ?string      $status,
        public readonly array        $photoUrls,
        public readonly ?CategoryDto $category,
        public readonly ?array       $tags,
    )
    {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $tags = [];
        foreach ($data['tags'] ?? [] as $tag) {
            $tags[] = TagDto::fromArray($tag);
        }

        return new self(
            id: isset($data['id']) && $data['id'] !== null ? (int)$data['id'] : null,
            name: $data['name'],
            status: PetStatus::tryFrom($data['status'] ?? '')->value,
            photoUrls: $data['photoUrls'] ?? [],
            category: isset($data['category']) ? CategoryDto::fromArray($data['category']) : null,
            tags: $tags
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category?->toArray(),
            'photoUrls' => $this->photoUrls,
            'tags' => isset($this->tags) ? array_map(fn(TagDto $tag) => $tag->toArray(), $this->tags) : [],
            'status' => $this->status,
        ];
    }

}
