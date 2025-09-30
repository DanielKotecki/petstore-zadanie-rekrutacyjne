<?php
declare(strict_types=1);

namespace App\DTO;

/**
 *
 */
class TagDto
{
    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
    )
    {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'],
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
        ];
    }
}
