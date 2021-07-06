<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Service;

use Xentral\Components\Database\Database;
use Xentral\Modules\DatevApi\Data\AccountSettingsData;

final class AccountingSettingsGateway
{
    /** @var Database */
    private $db;

    /**
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $project
     *
     * @return AccountSettingsData|null
     */
    public function findSettingsByProject(int $project): ?AccountSettingsData
    {
        $sql =
            'SELECT 
                ba.id AS `id`, 
                ba.beraternummer AS `adviser_number`, 
                ba.mandantennummer AS `mandator_number`, 
                ba.projekt AS `project`, 
                ba.zahlungsweise AS `payment_method`, 
                ba.wirtschaftsjahr AS `commercial_year`, 
                ba.sachkontenlaenge AS `nominal_account_length` 
                FROM `buchhaltungexport_einstellungen` AS `ba`
                WHERE ba.projekt = :project 
                LIMIT 1';

        $data = $this->db->fetchRow(
            $sql,
            [
                'project' => $project,
            ]
        );

        if (
            empty($data) ||
            empty($data['adviser_number'])) {
            return $this->findSettings();
        }

        return AccountSettingsData::fromDbState($data);
    }

    /**
     * @return AccountSettingsData|null
     */
    public function findSettings(): ?AccountSettingsData
    {
        $sql =
            'SELECT 
                ba.id AS `id`, 
                ba.beraternummer AS `adviser_number`, 
                ba.mandantennummer AS `mandator_number`, 
                ba.projekt AS `project`, 
                ba.zahlungsweise AS `payment_method`, 
                ba.wirtschaftsjahr AS `commercial_year`, 
                ba.sachkontenlaenge AS `nominal_account_length` 
                FROM `buchhaltungexport_einstellungen` AS `ba`
                LIMIT 1';

        $data = $this->db->fetchRow($sql);

        if (empty($data)) {
            return null;
        }

        return AccountSettingsData::fromDbState($data);
    }
}
