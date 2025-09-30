<?php
declare(strict_types=1);

namespace App\DTO;

use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

/**
 *
 */
class PetImageDto
{
    /**
     * @param int $petId Identyfikator Peta, do którego przesyłamy zdjęcie.
     * @param UploadedFile $file Obiekt pliku przesłanego przez formularz.
     * @param string|null $additionalMetadata Dodatkowe metadane.
     */
    public function __construct(
        public readonly int          $petId,
        public readonly UploadedFile $file,
        public readonly ?string      $additionalMetadata = null,
    )
    {
    }

    /**
     * @return int
     */
    public function getPetId(): int
    {
        return $this->petId;
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    /**
     * @return string|null
     */
    public function getAdditionalMetadata(): ?string
    {
        return $this->additionalMetadata ?? '';
    }


    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            petId: (int)$data['petId'],
            file: $data['file'], // Zostawiamy UploadedFile w tym miejscu
            additionalMetadata: $data['additionalMetadata'] ?? null,
        );
    }
    public function toArray(): array
    {
        if (!file_exists($this->file->getRealPath())) {
            throw new InvalidArgumentException("Plik nie istnieje lub jest niedostępny: " . $this->file->getRealPath());
        }

        return [
            'petId' => $this->petId,
            'file' => $this->file,
            'additionalMetadata' => $this->additionalMetadata,
        ];
    }
}
