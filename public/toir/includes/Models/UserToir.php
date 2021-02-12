<?php

class UserToir extends ToirModel
{

    public $table = 'users';
    public $softdelete = false;

    /**
     * @var UserToir
     */
    private static $cacheCurrent;

    /**
     * @var array
     */
    private static $cacheAvailableServiesIds = null;

    /**
     * @var array
     */
    private static $cacheAvailableWorkshopsIds = null;

    public $relations = [
        'service_id' => ['table' => 'departments_users', 'foreign_key' => 'department_id', 'owner_key' => 'user_id'],
        'workshop_id' => ['table' => 'users_workshops', 'foreign_key' => 'workshop_id', 'owner_key' => 'user_id'],
    ];

    /**
     * @return UserToir
     */
    public static function current(): ?UserToir
    {
        if($_SESSION['auth_id']) {
            if(!self::$cacheCurrent) {
                $user = self::find((int)$_SESSION['auth_id']);
                if($user->connected) {
                    self::$cacheCurrent = $user;
                }
            }
        } else {
            self::$cacheCurrent = null;
        }

        return self::$cacheCurrent;
    }

    /**
     * @return ToirModelBuilder
     */
    public function services(): ToirModelBuilder
    {
        return Service::filter(['ID' => $this->SERVICE_ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function workshops(): ToirModelBuilder
    {
        return Workshop::filter(['ID' => $this->WORKSHOP_ID]);
    }

    /**
     * @return string
     */
    public function fullname(): string
    {
        return $this->NAME . ' ' . $this->LAST_NAME;
    }

    /**
     * @return ToirModelBuilder
     */
    public function availableServices(): ToirModelBuilder
    {
        return $this->IS_ADMIN ? Service::filter([]) : $this->services();
    }

    /**
     * @return array
     */
    public function availableServicesIds(): array
    {
        if(is_null($this->cacheAvailableServiesIds)) {
            $this->cacheAvailableServiesIds = $this->IS_ADMIN ? array_keys($this->availableServices) : $this->SERVICE_ID;
        }
        return $this->cacheAvailableServiesIds;
    }

    /**
     * @return ToirModelBuilder
     */
    public function availableWorkshops(): ToirModelBuilder
    {
        if($this->IS_ADMIN || $this->ALL_WORKSHOPS) {
            $workshops = Workshop::filter([]);
        } else {
            $workshops = $this->workshops();
        }

        return $workshops;
    }

    /**
     * @return array
     */
    public function availableWorkshopsIds(): array
    {
        if(is_null($this->cacheAvailableWorkshopsIds)) {
            $this->cacheAvailableWorkshopsIds = array_keys($this->availableWorkshops);
        }
        return $this->cacheAvailableWorkshopsIds;
    }

    /**
     * @param Workshop|int $workshopId
     * @return bool
     */
    public function checkWorkshopOrFail($workshopId): bool
    {
        if ($workshopId) {
            if(is_a($workshopId, Workshop::class))  {
                $workshopId = $workshopId->ID;
            }
            $workshopsIds = $this->availableWorkshopsIds();
            if (!in_array($workshopId, $workshopsIds)) {
                header("Location: /");
            }
        }
        return true;
    }

    /**
     * @param Service|int $serviceId
     * @return bool
     */
    public function checkServiceOrFail($serviceId): bool
    {
        if ($serviceId) {
            if(is_a($serviceId, Service::class))  {
                $serviceId = $serviceId->ID;
            }
            $servicesIds = $this->availableServicesIds();
            if (!in_array($serviceId, $servicesIds)) {
                header("Location: /");
            }
        }
        return true;
    }

    /**
     * @param ToirModel
     * @return bool
     */
    public function checkModelOrFail(ToirModel $model): bool
    {
        if($model->WORKSHOP_ID) {
            $this->checkWorkshopOrFail($model->WORKSHOP_ID);
        }
        if($model->SERVICE_ID) {
            $this->checkServiceOrFail($model->SERVICE_ID);
        }
        return true;
    }

    public function getId()
    {
        return $this->id;
    }
}

