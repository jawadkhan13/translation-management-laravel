<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Translation;
use App\Repositories\TranslationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TranslationService
{
    protected TranslationRepositoryInterface $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * Create a translation and associate tags.
     */
    public function createTranslation(array $data): Translation
    {
        return DB::transaction(function () use ($data) {
            $translation = $this->translationRepository->create($data);

            if (isset($data['tags']) && is_array($data['tags'])) {
                $tagIds = [];
                foreach ($data['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                $translation->tags()->sync($tagIds);
            }

            return $translation->load('tags');
        });
    }

    /**
     * Update a translation and its tag associations.
     */
    public function updateTranslation(int $id, array $data): Translation
    {
        return DB::transaction(function () use ($id, $data) {
            $translation = $this->translationRepository->find($id);
            if (!$translation) {
                throw new \Exception("Translation not found");
            }

            $translation = $this->translationRepository->update($translation, $data);
            if (isset($data['tags']) && is_array($data['tags'])) {
                $tagIds = [];
                foreach ($data['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                $translation->tags()->sync($tagIds);
            }

            return $translation->load('tags');
        });
    }

    /**
     * Retrieve a translation by its ID.
     */
    public function getTranslation(int $id): ?Translation
    {
        return $this->translationRepository->find($id);
    }

    /**
     * Search translations based on criteria.
     */
    public function searchTranslations(array $params, int $perPage = 50): LengthAwarePaginator
    {
        return $this->translationRepository->search($params, $perPage);
    }

    /**
     * Export all translations.
     */
    public function exportTranslationsStream(): StreamedResponse
    {
        $stream = function () {
            echo '['; 
            $first = true;
    
            // Use the repository method to process translations in chunks.
            $this->translationRepository->exportChunks(function ($translations) use (&$first) {
                foreach ($translations as $translation) {
                    if (!$first) {
                        echo ',';
                    } else {
                        $first = false;
                    }
                    echo json_encode([
                        'id'              => $translation->id,
                        'locale'          => $translation->locale,
                        'translation_key' => $translation->translation_key,
                        'content'         => $translation->content,
                        'tags'            => $translation->tags->pluck('name')->toArray(),
                    ]);
                    flush(); 
                }
            });
            echo ']'; 
        };
    
        $response = new StreamedResponse($stream);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function processTranslationBatch(int $batchSize, array $tags): Collection
    {
        $translations = $this->translationRepository->createTranslations($batchSize);

        $pivotData = [];
        foreach ($translations as $translation) {
            foreach ($tags as $tag) {
                if (rand(0, 1)) {
                    $pivotData[] = [
                        'translation_id' => $translation->id,
                        'tag_id'         => $tag->id,
                    ];
                }
            }
        }

        // Bulk insert the pivot data
        $this->translationRepository->insertTagAssociations($pivotData);

        return $translations;
    }
}
