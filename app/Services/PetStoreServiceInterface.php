<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\PetDto;
use App\DTO\PetImageDto;
use App\Enums\PetStatus;

/**
 *
 */
interface PetStoreServiceInterface
{
    /**
     * @param string $status
     * @return array
     */
    public function getAllPetsByStatus(string $status = PetStatus::AVAILABLE->value): array;

    /**
     * @param int $id
     * @return array
     */
    public function getPetById(int $id): array;

    /**
     * @param PetDto $dto
     * @return array
     */
    public function createPet(PetDto $dto): array;

    /**
     * @param PetDto $dto
     * @return array
     */
    public function updatePet(PetDto $dto): array;

    /**
     * @param int $id
     * @return bool
     */
    public function deletePet(int $id): bool;

    /**
     * @param PetImageDto $dto
     * @return array
     */
    public function uploadPetImage(PetImageDto $dto): array;
}
