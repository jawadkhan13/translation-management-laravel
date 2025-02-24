<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface TranslationRepositoryInterface
{
    public function create(array $data): Translation;
    public function update(Translation $translation, array $data): Translation;
    public function find(int $id): ?Translation;
    public function search(array $params, int $perPage = 50): LengthAwarePaginator;
    public function exportChunks(callable $callback): void;
    
    public function createTranslations(int $count): Collection;
    public function insertTagAssociations(array $pivotData): void;
}
