<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationRequest;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TranslationController extends Controller
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    
    ppublic function index(Request $request): JsonResponse
    {
        $params = $request->only(['translation_key', 'content', 'tag']);
        $results = $this->translationService->searchTranslations($params);
        return response()->json($results);
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request): JsonResponse
    {
        $translation = $this->translationService->createTranslation(
            $request->only(['locale', 'translation_key', 'content', 'tags'])
        );

        return response()->json($translation, 201);
    }

   
    public function show(int $id): JsonResponse
    {
        $translation = $this->translationService->getTranslation($id);
        if (!$translation) {
            return response()->json(['error' => 'Translation not found'], 404);
        }
        return response()->json($translation);
    }

   
    public function edit(string $id)
    {
        //
    }

    
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $translation = $this->translationService->updateTranslation(
                $id,
                $request->only(['locale', 'translation_key', 'content', 'tags'])
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Translation not found'], 404);
        }

        return response()->json($translation);
    }

    
    public function destroy(string $id)
    {
        //
    }

    
    public function export(): StreamedResponse
    {
        return $this->translationService->exportTranslationsStream();
    }
}
