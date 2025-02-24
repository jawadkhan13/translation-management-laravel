<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TranslationRepository implements TranslationRepositoryInterface
{
    public function create(array $data): Translation
    {
        return Translation::create($data);
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update($data);
        return $translation->fresh();
    }

    public function find(int $id): ?Translation
    {
        return Translation::with('tags')->find($id);
    }

    public function search(array $params, int $perPage = 50): LengthAwarePaginator
    {
        $query = Translation::with('tags:id,name')->select(['id', 'locale', 'translation_key', 'content']);

        if (!empty($params['translation_key'])) {
            $query->where('translation_key', 'like', '%' . $params['translation_key'] . '%');
        }
        if (!empty($params['content'])) {
            $query->where('content', 'like', '%' . $params['content'] . '%');
        }
        if (!empty($params['tag'])) {
            $query->whereHas('tags', function ($q) use ($params) {
                $q->where('name', 'like', '%' . $params['tag']. '%');
            });
        }

        return $query->paginate($perPage);
    }

    public function exportChunks(callable $callback): void
    {
        Translation::with('tags:id,name')
            ->select(['id', 'locale', 'translation_key', 'content'])
            ->chunkById(1000, $callback);
    }

    public function createTranslations(int $count): Collection
    {
        return Translation::factory()->count($count)->create();
    }

    public function insertTagAssociations(array $pivotData): void
    {
        if (!empty($pivotData)) {
            DB::table('tag_translation')->insertOrIgnore($pivotData);
        }
    }
}
