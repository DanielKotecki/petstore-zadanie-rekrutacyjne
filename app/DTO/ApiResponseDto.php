<?php

namespace App\DTO;

/**
 *
 */
class ApiResponseDto
{
    /**
     * @param int $code
     * @param string $type
     * @param string $message
     */
    public function __construct(
        public readonly int    $code,
        public readonly string $type,
        public readonly string $message,
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
            code: $data['code'] ?? 500,
            type: $data['type'] ?? 'Błąd',
            message: $data['message'] ?? "Błąd"
        );
    }
}
