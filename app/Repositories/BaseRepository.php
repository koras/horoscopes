<?php

namespace App\Repositories;

use App\Contracts\DTO\ArrayableDtoInterface;
use App\Contracts\Repositories\RepositoryInterface;
use App\Traits\SnakeCasingTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    use SnakeCasingTrait;

    public function __construct(
        protected Model $model
    ) {
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * $userRepository->with(['posts']);
     * метода для загрузки связей
     * @param array $relations
     * @return Collection
     */
    public function with(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }

    /**
     * $userRepository->with(['posts']);
     * метода для загрузки связей
     * @param array $relations
     * @return Collection
     */
    public function withFirst(int $id, array $relations)
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    /**
     * Пагинация со связями
     * @param array $relations
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function withPaginated(array $relations, int $perPage = 15)
    {
        return $this->model->with($relations)->paginate($perPage);
    }

    /**
     * Пагинация с сортировками
     * @param array $relations
     * @param string|null $orderBy
     * @param string $direction
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function withPaginatedAndOrdered(
        array $relations,
        string $orderBy = null,
        string $direction = 'asc',
        int $perPage = 15
    ) {
        $query = $this->model->with($relations);

        if ($orderBy) {
            $query->orderBy($orderBy, $direction);
        }

        return $query->paginate($perPage);
    }


    /**
     *  метод для фильтрации через наличие связей (has)
     *
     * $usersWithPosts = $userRepository->whereHasRelation('posts', function($query) {
     * $query->where('published', true);
     * });
     *
     *
     * @param string $relation
     * @param callable $callback
     * @return mixed
     */
    public function whereHasRelation(string $relation, callable $callback)
    {
        return $this->model->whereHas($relation, $callback)->get();
    }

    // Загрузка определённых полей из связанной модели
    public function withRelationFields(string $relation, array $fields, int|string $id)
    {
        return $this->model->with([
            $relation => function ($query) use ($fields) {
                $query->select($fields);
            }
        ])->findOrFail($id);
    }

    public function existsById(int|string $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    public function getExistingIds(array $needleIds): array
    {
        return $this->model->whereIn('id', $needleIds)->pluck('id')->toArray();
    }

    public function findInField(string $fieldName, array $values): Collection|array
    {
        return $this->model->whereIn($fieldName, $values)->get();
    }

    public function find(int|string $id)
    {
        return $this->model->findOrFail($id);
    }

    public function findBy(array $params): Collection|array
    {
        $query = $this->model->newQuery();
        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    public function findOneBy(array $params): ?Model
    {
        $query = $this->model->newQuery();
        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    public function create(ArrayableDtoInterface $dto)
    {
        $dataArray = $dto->toArray();
        $dataToModel = $this->arrayKeysToSnakeCase($dataArray);
        return $this->model->create($dataToModel);
    }

    public function update(int|string $id, array $params)
    {
        $dataToModel = $this->arrayKeysToSnakeCase($params);

        $record = $this->find($id);
        $record->update($dataToModel);
        return $record;
    }

    public function delete(int|string $id)
    {
        $record = $this->find($id);
        return $record->delete();
    }
}
