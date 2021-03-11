<?php

namespace App\Domains\System\Services;

use Exception;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Domains\System\Models\Organisation;

/**
 * Class OrganisationService.
 */
class OrganisationService extends BaseService
{
    /**
     * OrganisationService constructor.
     *
     * @param  Organisation  $organisation
     */
    public function __construct(Organisation $organisation)
    {
        $this->model = $organisation;
    }

    /**
     * @param  array  $data
     *
     * @return Organisation
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): Organisation
    {
        DB::beginTransaction();

        try {
            $organisation = $this->model::create([
                'organisation_name' => $data['organisation_name'] ?? null,
                'organisation_type' => $data['organisation_type'] ?? null,
                'building_name_no' => $data['building_name_no'] ?? null,
                'street' => $data['street'] ?? null,
                'address_line_2' => $data['address_line_2'] ?? null,
                'county' => $data['county'] ?? null,
                'city' => $data['city'] ?? null,
                'postcode' => $data['postcode'] ?? null,
                'logo' => $data['logo'] ?? null,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            throw new GeneralException(__('There was a problem creating this Organisation. Please try again.'));
        }

        DB::commit();

        return $organisation;
    }

    /**
     * @param  Organisation  $organisation
     * @param  array  $data
     *
     * @return Organisation
     * @throws \Throwable
     */
    public function update(Organisation $organisation, array $data = []): Organisation
    {
        DB::beginTransaction();

        try {
            $organisation->update([
                'organisation_name' => $data['organisation_name'] ?? null,
                'organisation_type' => $data['organisation_type'] ?? null,
                'building_name_no' => $data['building_name_no'] ?? null,
                'street' => $data['street'] ?? null,
                'address_line_2' => $data['address_line_2'] ?? null,
                'county' => $data['county'] ?? null,
                'city' => $data['city'] ?? null,
                'postcode' => $data['postcode'] ?? null,
                'logo' => $data['logo'] ?? null,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw new GeneralException(__('There was a problem updating this Organisation. Please try again.'));
        }

        DB::commit();

        return $organisation;
    }

    /**
     * @param  Organisation  $organisation
     *
     * @return Organisation
     * @throws GeneralException
     */
    public function delete(Organisation $organisation): Organisation
    {
        if ($this->deleteById($organisation->id)) {
            return $organisation;
        }

        throw new GeneralException('There was a problem deleting this Organisation. Please try again.');
    }

    /**
     * @param Organisation $organisation
     *
     * @throws GeneralException
     * @return Organisation
     */
    public function restore(Organisation $organisation): Organisation
    {
        if ($organisation->restore()) {
            return $organisation;
        }

        throw new GeneralException(__('There was a problem restoring this Organisation. Please try again.'));
    }

    /**
     * @param  Organisation  $organisation
     *
     * @return bool
     * @throws GeneralException
     */
    public function destroy(Organisation $organisation): bool
    {
        if ($organisation->forceDelete()) {
            return true;
        }

        throw new GeneralException(__('There was a problem permanently deleting this Organisation. Please try again.'));
    }
}
