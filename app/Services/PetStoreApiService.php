<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\PetDto;
use App\DTO\PetImageDto;
use App\Enums\Api\HttpMethod;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


/**
 *
 */
class PetStoreApiService implements PetStoreServiceInterface
{
    /**
     * @var PendingRequest
     */
    protected PendingRequest $httpClient;

    /**
     *
     */
    public function __construct()
    {
        $this->httpClient = Http::baseUrl(config('services.petstore.base_url'))
            ->timeout(10)
            ->retry(3, 100);
    }


    /**
     * @param HttpMethod $method
     * @param string $uri
     * @param array $data
     * @return array|bool
     * @throws Exception
     */
    protected function sendRequest(HttpMethod $method, string $uri, array $data = []): array|bool
    {
        try {
            $request = $this->httpClient;

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $request = $request->attach(
                    'file',
                    file_get_contents($data['file']->getRealPath()),
                    $data['file']->getClientOriginalName()
                );
                $requestData = ['additionalMetadata' => $data['additionalMetadata'] ?? null];
            } else {
                $request = $request->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]);
                $requestData = $data;
            }

            $response = $request->{$method->value}($uri, $requestData);

            if ($response->failed()) {
                $response->throw();
            }
            return $method === HttpMethod::DELETE ? true : ($response->json() ?? []);
        } catch (RequestException $e) {
            if ($e->response->status() === 404) {
                Log::error("Błąd 404: Zasób nie istnieje w $uri", ['status' => 404]);
                throw new Exception("Błąd 404: Zasób nie istnieje", 404);
            }
            Log::error("Błąd API (Status: {$e->response->status()}): {$e->getMessage()}", ['status' => $e->response->status()]);
            throw new Exception("Błąd API (Status: {$e->response->status()}): {$e->getMessage()}", $e->response->status());
        } catch (Exception $e) {
            Log::error("Błąd inny: {$e->getMessage()}");
            throw new Exception("Błąd inny: {$e->getMessage()}");
        }
    }

    /**
     * @param string $status
     * @return array
     * @throws Exception
     */
    public function getAllPetsByStatus(string $status = 'available'): array
    {

        return $this->sendRequest(HttpMethod::GET, '/pet/findByStatus', ['status' => $status]);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getPetById(int $id): array
    {
        return $this->sendRequest(HttpMethod::GET, "/pet/$id");
    }

    /**
     * @param PetDto $dto
     * @return array
     * @throws Exception
     */
    public function createPet(PetDto $dto): array
    {
        return $this->sendRequest(HttpMethod::POST, '/pet', $dto->toArray());
    }

    /**
     * @param PetDto $dto
     * @return array
     * @throws Exception
     */
    public function updatePet(PetDto $dto): array
    {
        return $this->sendRequest(HttpMethod::PUT, '/pet', $dto->toArray());
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deletePet(int $id): bool
    {
        return $this->sendRequest(HttpMethod::DELETE, "/pet/$id");
    }
    /**
     * @param PetImageDto $dto
     * @return array
     * @throws Exception
     */
    public function uploadPetImage(PetImageDto $dto): array
    {
        return $this->sendRequest(HttpMethod::POST, "/pet/{$dto->getPetId()}/uploadImage", $dto->toArray());
    }

}
