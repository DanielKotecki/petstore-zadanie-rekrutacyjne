<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\ApiResponseDto;
use App\DTO\PetDto;
use App\DTO\PetImageDto;
use App\Enums\PetStatus;
use App\Http\Requests\PetUploadImageRequest;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Services\PetStoreServiceInterface;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 *
 */
class PetController extends Controller
{
    /**
     * @param PetStoreServiceInterface $petStoreService
     */
    public function __construct(
        protected PetStoreServiceInterface $petStoreService
    )
    {
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
            $statusQuery = $request->query('status', PetStatus::AVAILABLE->value);
            $status = PetStatus::tryFrom($statusQuery) ?? PetStatus::AVAILABLE;
            $pets = $this->petStoreService->getAllPetsByStatus($status->value);
            return view('pets.index', compact('pets'));
        } catch (\Exception $e) {
            session()->flash('error', 'Błąd pobierania listy Pet: ' . $e->getMessage());
            return view('pets.index');
        }
    }

    /**
     * @param string $id
     * @return View|RedirectResponse
     */
    public function show(string $id): View|RedirectResponse

    {
        try {
            $pet = PetDto::fromArray($this->petStoreService->getPetById((int)$id));
            return view('pets.show', compact('pet'));
        } catch (\Exception $e) {
            session()->flash('error', 'Błąd pobierania Pet: ' . $e->getMessage());
            return redirect()->route('pets.index');
        }
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('pets.form');
    }

    /**
     * @param StorePetRequest $request
     * @return RedirectResponse
     */
    public function store(StorePetRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $dto = PetDto::fromArray($validated);
            $this->petStoreService->createPet($dto);
            session()->flash('success', 'Pet został stworzony.');
            return redirect()->route('pets.index');
        } catch (Exception $e) {
            session()->flash('error', 'Błąd dodawania Pet: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {

        try {
            $pet = PetDto::fromArray($this->petStoreService->getPetById($id));
            return view('pets.form', compact('pet'));
        } catch (Exception $e) {
            session()->flash('error', 'Błąd pobierania Pet do aktualizacji: ' . $e->getMessage());
            return view('pets.form');
        }
    }


    /**
     * @param UpdatePetRequest $request
     * @return RedirectResponse
     */
    public function update(UpdatePetRequest $request): RedirectResponse
    {
        try {
            $dto = PetDto::fromArray($request->validated());
            $this->petStoreService->updatePet($dto);
            session()->flash('success', 'Pet Został zaktualizowany!');
            return redirect()->route('pets.index');
        } catch (\Throwable $e) {
            session()->flash('error', 'Błąd aktualizacji: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->petStoreService->deletePet($id);
            session()->flash('success', "Pet  o id:{$id} został usunięty porpawnie.");
            return redirect()->route('pets.index');
        } catch (Exception $e) {
            session()->flash('error', "Błąd usuwania Pet o id:{$id}: " . $e->getMessage());
            return back();
        }
    }

    /**
     * @param int $id
     * @return View|RedirectResponse
     */
    public function uploadImageShow(int $id): View|RedirectResponse
    {
        try {
            $pet = PetDto::fromArray($this->petStoreService->getPetById($id));
            return view('pets.form-upload-image', compact('pet'));
        } catch (\Exception $e) {
            session()->flash('error', 'Błąd pobierania Pet do uploadu: ' . $e->getMessage());
            return redirect()->route('pets.index');
        }
    }

    /**
     * @param PetUploadImageRequest $request
     * @return RedirectResponse
     */
    public function uploadImage(PetUploadImageRequest $request): RedirectResponse
    {
        try {
            $petsImage = PetImageDto::fromArray($request->validated());

            $result = ApiResponseDto::fromArray($this->petStoreService->uploadPetImage($petsImage));
            session()->flash('success', "Upload dla Pet ID: {$petsImage->getPetId()} udany. API Message: {$result->message}");
            return redirect()->route('pets.index');

        } catch (Exception $e) {
            session()->flash('error', 'Błąd uploadu pliku dla Pet ID: ' . $petsImage->getPetId() . ': ' . $e->getMessage());
            return back()->withInput();
        }
    }
}
