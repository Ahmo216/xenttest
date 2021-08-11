<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Service;

use DateTimeInterface;
use Xentral\Components\Database\Database;
use Xentral\Modules\DatevApi\Data\DocumentData;

final class DocumentGateway
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
     * @param int               $projectId
     * @param bool              $isZeroEuroAllowed
     * @param DateTimeInterface $from
     * @param DateTimeInterface $till
     *
     * @return DocumentData[]|array
     */
    public function findDataFromInvoice(
        int $projectId,
        bool $isZeroEuroAllowed,
        DateTimeInterface $from,
        DateTimeInterface $till
    ): array {
        $subWhere = $this->getSubWhere($projectId, $isZeroEuroAllowed, 'rechnung');

        $sql =
            "SELECT 
            b.projekt AS `project`,
            b.datum AS `document_date`, 
            DATE_FORMAT(b.datum,'%Y-%m') AS `year_month`, 
            b.belegnr AS `document_number`, 
            b.soll AS `debit`, 
            b.waehrung AS `currency`, 
            b.id AS `document_id`, 
            b.adresse AS `address_id`, 
            b.ustid AS `vat_number`, 
            IF(
                a.kundennummer_buchhaltung!='',
                a.kundennummer_buchhaltung,
                b.kundennummer
            ) AS `customer_number`, 
            b.name AS `customer_Name`, 
            b.ort AS `customer_city`, 
            b.land AS `customer_country`, 
            b.ust_befreit AS `vat_free`, 
            au.internet AS `internet`,
            au.transaktionsnummer AS `transaction_number`
            FROM `rechnung` AS `b` 
            LEFT JOIN `adresse` AS `a` ON a.id = b.adresse 
            LEFT JOIN `auftrag` AS `au` ON au.id = b.auftragid
            WHERE b.datum BETWEEN :from AND :till 
            AND b.status != 'angelegt'
            " . $subWhere;

        $results = $this->db->fetchAll(
            $sql,
            [
                'from' => $from->format('Y-m-d'),
                'till' => $till->format('Y-m-d'),
            ]
        );

        if (empty($results)) {
            return [];
        }

        $return = [];
        foreach ($results as $result) {
            $return[] = DocumentData::fromDbState($result);
        }

        return $return;
    }

    /**
     * @param int               $projectId
     * @param bool              $isZeroEuroAllowed
     * @param DateTimeInterface $from
     * @param DateTimeInterface $till
     *
     * @return DocumentData[]|array
     */
    public function findDataFromCreditNote(
        int $projectId,
        bool $isZeroEuroAllowed,
        DateTimeInterface $from,
        DateTimeInterface $till
    ): array {
        $subWhere = $this->getSubWhere($projectId, $isZeroEuroAllowed, 'gutschrift');

        $sql =
            "SELECT 
            b.projekt AS `project`,
            b.datum AS `document_date`, 
            DATE_FORMAT(b.datum,'%Y-%m') AS `year_month`, 
            b.belegnr AS `document_number`, 
            b.soll AS `debit`, 
            b.waehrung AS `currency`, 
            b.id AS `document_id`, 
            b.adresse AS `address_id`, 
            b.ustid AS `vat_number`, 
            IF(
                a.kundennummer_buchhaltung!='',
                a.kundennummer_buchhaltung,
                b.kundennummer
            ) AS `customer_number`, 
            b.name AS `customer_Name`, 
            b.ort AS `customer_city`, 
            b.land AS `customer_country`, 
            b.ust_befreit AS `vat_free`, 
            au.internet AS `internet`
            FROM gutschrift AS b 
            LEFT JOIN `adresse` AS `a` ON a.id = b.adresse 
            LEFT JOIN `rechnung` AS `r` ON r.id = b.rechnungid
            LEFT JOIN `auftrag` AS `au` ON au.id = r.auftragid
            WHERE b.datum BETWEEN :from AND :till  
            AND b.status != 'angelegt' 
            " . $subWhere;

        $results = $this->db->fetchAll(
            $sql,
            [
                'from' => $from->format('Y-m-d'),
                'till' => $till->format('Y-m-d'),
            ]
        );

        if (empty($results)) {
            return [];
        }

        $return = [];
        foreach ($results as $result) {
            $return[] = DocumentData::fromDbState($result);
        }

        return $return;
    }

    /**
     * @param int               $projectId
     * @param bool              $isZeroEuroAllowed
     * @param DateTimeInterface $from
     * @param DateTimeInterface $till
     * @param int               $liabilityDateType
     *
     * @return DocumentData[]|array
     */
    public function findDataFromLiability(
        int $projectId,
        bool $isZeroEuroAllowed,
        DateTimeInterface $from,
        DateTimeInterface $till,
        int $liabilityDateType
    ): array {
        $subWhere = $this->getSubWhere($projectId, $isZeroEuroAllowed, 'verbindlichkeit');

        if ($liabilityDateType === 1) {
            $dateFilter = 'v.rechnungsdatum';
        } else {
            $dateFilter = 'v.eingangsdatum';
        }

        $sql =
            "SELECT 
            v.projekt `project`,
            {$dateFilter} AS `document_date`, 
            DATE_FORMAT({$dateFilter},'%Y-%m') AS `year_month`, 
            IF(v.rechnung <> '', v.rechnung, v.belegnr) AS `document_number`, 
            v.betrag AS `debit`,
            v.waehrung AS `currency`, 
            v.id AS `document_id`, 
            v.adresse AS `address_id`, 
            IF(
                v.ustid!='', 
                v.ustid, 
                a.ustid
            ) AS `vat_number`,
            '' AS `customer_number`,
            a.name AS `customer_Name`, 
            a.ort AS `customer_city`, 
            a.land AS `customer_country`,
            0 AS `vat_free`, 
            '' AS `internet`,
            vk.id AS `account_assignment`,
            v.belegnr
          FROM `verbindlichkeit` AS `v`
          LEFT JOIN `verbindlichkeit_kontierung` AS `vk` ON v.id = vk.verbindlichkeit
          LEFT JOIN `adresse` AS `a` ON a.id = v.adresse 
          WHERE {$dateFilter} BETWEEN :from AND :till
          AND v.status_beleg != 'angelegt' 
          AND v.status_beleg != 'storniert' 
          " . $subWhere . ' 
          GROUP BY v.id';

        $results = $this->db->fetchAll(
            $sql,
            [
                'from' => $from->format('Y-m-d'),
                'till' => $till->format('Y-m-d'),
                'date_filter' => $dateFilter,
            ]
        );

        if (empty($results)) {
            return [];
        }

        $this->ensureLiabilitiesWithAccountAssignments($results);

        $return = [];
        foreach ($results as $result) {
            $return[] = DocumentData::fromDbState($result);
        }

        return $return;
    }

    private function ensureLiabilitiesWithAccountAssignments(array $results): void
    {
        $liabilitiesWithoutAssignments = array_map(
            function (array $liability) {
                return '<a target="_blank" href="index.php?module=verbindlichkeit&action=edit&id='
                    . $liability['document_id']
                    . '">'
                    . ($liability['belegnr'] ?: $liability['document_id'])
                    . '</a>';
            },
            array_filter(
                $results,
                function (array $liability) {
                return $liability['account_assignment'] === null;
            }
            )
        );
        if (!empty($liabilitiesWithoutAssignments)) {
            throw new \RuntimeException(trans_choice('{1}Die Verbindlichkeit :liabilities hat keine Vorkontierung|Die Verbindlichkeiten :liabilities haben keine Vorkontierung.', count($liabilitiesWithoutAssignments), ['liabilities' => implode(', ', $liabilitiesWithoutAssignments)]));
        }
    }

    /**
     * @param int    $projectId
     * @param bool   $isZeroEuroAllowed
     * @param string $doctype
     *
     * @return string
     */
    private function getSubWhere(int $projectId, bool $isZeroEuroAllowed, string $doctype): string
    {
        $subWhereProject = '';
        if ($projectId > 0) {
            if ($doctype === 'verbindlichkeit') {
                $subWhereProject = " AND v.projekt='${projectId}' ";
            } else {
                $subWhereProject = " AND b.projekt='${projectId}' ";
            }
        }
        $zeroEuroInvoice = '';
        if (!$isZeroEuroAllowed) {
            if ($doctype === 'rechnung' || $doctype === 'gutschrift') {
                $zeroEuroInvoice = ' b.soll != 0 ';
            }
            if ($doctype === 'verbindlichkeit') {
                $zeroEuroInvoice = ' v.betrag != 0 ';
            }
        }

        if ($zeroEuroInvoice != '') {
            $subWhereProject .= 'AND ' . $zeroEuroInvoice;
        }

        return $subWhereProject;
    }
}
