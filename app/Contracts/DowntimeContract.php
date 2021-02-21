<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface DowntimeContract
 * @package App\Contract
 */
interface DowntimeContract
{

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * 
     * @return Collection
     */
    public function getDowntimesGroupedDate(string $dateFrom, string $dateTo): Collection;

    /**
     * @param string $date
     * 
     * @return Collection
     */
    public function getDowntimesGroupedType(string $date): Collection;

    /**
     * @param string $date
     * @param string $type
     * @param int|null $parentEquipmentId
     * 
     * @return Collection
     */
    public function getDowntimesGroupedEquipment(string $date, string $type, ?int $parentEquipmentId): Collection;

    /**
     * @param string $date
     * @param string|null $type
     * @param int|null $equipmentId
     * 
     * @return Collection
     */
    public function getOperations(string $date, ?string $type, ?int $equipmentId): Collection;
}