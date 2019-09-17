<?php

namespace App\Controllers\AdminPanel;

use App\Models\DataBaseMigration;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\RememberedClient;
use App\Models\ShopToken;
use App\Models\LockedPhone;

/**
 * Class DataBaseJob.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\AdminPanel
 */
class DataBaseJob extends AdminPanel
{
    /**
     * Clean up database (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function cleanUpAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        RememberedClient::deleteExpiredRecords();
        ShopToken::deleteExpiredRecords();
        LockedPhone::deleteExpiredRecords();

        return $this->sendJsonResponse(['data' => ['success' => true]]);
    }

    /**
     * Has not completed db migrations (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function hasNotCompletedMigrationsAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        return $this->sendJsonResponse([
            'data' => [
                'has_not_completed_db_migrations' => ! empty(DataBaseMigration::getNotCompletedMigrations()),
            ],
        ]);
    }

    /**
     * Update (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        $db_migrations = DataBaseMigration::getNotCompletedMigrations();

        foreach ($db_migrations as $db_migration) {
            if (method_exists(DataBaseMigration::class, $db_migration['name'])) {
                if (! call_user_func(array(DataBaseMigration::class, $db_migration['name']))) {
                    return $this->sendJsonResponse(['data' => ['success' => false]]);
                }
            }
        }

        return $this->sendJsonResponse(['data' => ['success' => true]]);
    }
}
