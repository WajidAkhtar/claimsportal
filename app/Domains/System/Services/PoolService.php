<?php

namespace App\Domains\System\Services;

use Exception;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Domains\System\Models\Pool;

/**
 * Class PoolService.
 */
class PoolService extends BaseService
{
    /**
     * PoolService constructor.
     *
     * @param  Pool  $pool
     */
    public function __construct(Pool $pool)
    {
        $this->model = $pool;
    }

    /**
     * @param  array  $data
     *
     * @return Pool
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): Pool
    {
        DB::beginTransaction();

        try {
            $pool = $this->model::create([
                'code' => $data['code'],
                'name' => $data['name'],
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            throw new GeneralException(__('There was a problem creating this pool. Please try again.'));
        }

        DB::commit();

        return $pool;
    }

    /**
     * @param  Pool  $pool
     * @param  array  $data
     *
     * @return Pool
     * @throws \Throwable
     */
    public function update(Pool $pool, array $data = []): Pool
    {
        DB::beginTransaction();

        try {
            $pool->update([
                'code' => $data['code'],
                'name' => $data['name'],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw new GeneralException(__('There was a problem updating this pool. Please try again.'));
        }

        DB::commit();

        return $pool;
    }

    /**
     * @param  Pool  $pool
     *
     * @return Pool
     * @throws GeneralException
     */
    public function delete(Pool $pool): Pool
    {
        if ($this->deleteById($pool->id)) {
            return $pool;
        }

        throw new GeneralException('There was a problem deleting this pool. Please try again.');
    }

    /**
     * @param Pool $pool
     *
     * @throws GeneralException
     * @return Pool
     */
    public function restore(Pool $pool): Pool
    {
        if ($pool->restore()) {
            return $pool;
        }

        throw new GeneralException(__('There was a problem restoring this pool. Please try again.'));
    }

    /**
     * @param  Pool  $pool
     *
     * @return bool
     * @throws GeneralException
     */
    public function destroy(Pool $pool): bool
    {
        if ($pool->forceDelete()) {
            return true;
        }

        throw new GeneralException(__('There was a problem permanently deleting this pool. Please try again.'));
    }
}
