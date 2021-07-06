<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\DataTable;

class TurnoverThresholdList
{
    public static function getTableDefinition(): array
    {
        $yes = __('ja');
        $storage = __('Lager');
        $mainStorage = __('Hauptlager');
        $noStorage = __('kein Lager');
        $format = 'de_DE';
        $tableDefinitions = [];
        $tableDefinitions['allowed']['lieferschwelle'] = ['list'];
        $tableDefinitions['heading'] = [
            __('Empfängerland'),
            __('EU'),
            __('Lieferschwelle'),
            __('Währung'),
            __('Ust-ID'),
            __('Steuersatz normal'),
            __('Steuersatz ermäßigt'),
            __('Steuersatz spezial'),
            __('Überschreitung ab Datum'),
            __('Aktueller Umsatz'),
            __('Lager'),
            __('Aktiv'),
            __('Menü'),
        ];
        $tableDefinitions['width'] = ['10%', '1%', '10%', '5%', '10%', '10%', '10%', '10%', '10%', '10%', '5%', '1%', '1%'];
        $colStorage = "IF(l.use_storage = 1, '{$storage}', IF(l.use_storage = 2, '{$mainStorage}','{$noStorage}'))";
        $activeCol = "IF(l.use_storage=1 OR l.use_storage=2 OR l.verwenden = 1, '{$yes}','-')";
        $tableDefinitions['findcols'] = [
            'l.empfaengerland',
            "IF(c.eu = 1 AND l.use_storage = 0, '{$yes}', '-')",
            'l.lieferschwelleeur',
            "IF(l.currency IS NULL OR l.currency = '', 'EUR', l.currency)",
            'l.ustid',
            'l.steuersatznormal',
            'l.steuersatzermaessigt',
            'l.steuersatzspezial',
            'l.ueberschreitungsdatum',
            'l.aktuellerumsatz',
            'l.use_storage',
            $activeCol,
            'l.id',
        ];
        $dateFormat = '%d.%m.%Y';
        $tableDefinitions['searchsql'] = [
            'l.empfaengerland',
            "FORMAT(l.lieferschwelleeur, 2, '{$format}')",
            'l.ustid',
            "FORMAT(l.steuersatznormal, 2, '{$format}')",
            "FORMAT(l.steuersatzermaessigt, 2, '{$format}')",
            "FORMAT(l.steuersatzspezial, 2, '{$format}')",
            "IF(l.ueberschreitungsdatum = '0000-00-00', '-', DATE_FORMAT(l.ueberschreitungsdatum, '{$dateFormat}'))",
            "FORMAT(l.aktuellerumsatz, 2 ,'{$format}')",
            $colStorage,
        ];
        $tableDefinitions['defaultorder'] = 1;
        $tableDefinitions['defaultorderdesc'] = 0;
        $tableDefinitions['alignright'] = [3, 6, 7, 8, 10];
        $tableDefinitions['numbercols'] = [2, 5, 6, 7, 9];
        $tableDefinitions['sumcol'] = [10];
        $tableDefinitions['menu'] = trim((view('modules.turnover_threshold.list_menu_column'))->render());
        $tableDefinitions['where'] = ' l.id > 0';
        $tableDefinitions['sql'] = self::getSqlColumn($format, $dateFormat, $colStorage, $activeCol);
        $tableDefinitions['count'] = "SELECT COUNT(l.id) FROM `lieferschwelle` AS `l` WHERE {$tableDefinitions['where']}";
        $tableDefinitions['fastcount'] = 'SELECT COUNT(l.id) FROM `lieferschwelle` AS `l` LEFT JOIN `laender` AS `c` ON l.empfaengerland = c.iso';

        return $tableDefinitions;
    }

    private static function getSqlColumn(string $format, string $dateFormat, string $colStorage, string $activeCol): string
    {
        $yes = __('ja');

        return "SELECT l.id,
          l.empfaengerland,
          IF(c.eu = 1 AND l.use_storage = 0, '{$yes}', '-'),
          FORMAT(l.lieferschwelleeur, 2, '{$format}'),
          IF(l.currency IS NULL OR l.currency = '', 'EUR', l.currency),
          l.ustid,
          FORMAT(l.steuersatznormal, 2, '{$format}'),
          FORMAT(l.steuersatzermaessigt, 2, '{$format}'),
          FORMAT(l.steuersatzspezial, 2, '{$format}'),
          IF(l.ueberschreitungsdatum = '0000-00-00', '-', DATE_FORMAT(l.ueberschreitungsdatum, '{$dateFormat}')), 
          FORMAT(l.aktuellerumsatz, 2, '{$format}'),
          {$colStorage},
          {$activeCol},
          l.id
        FROM `lieferschwelle` AS `l`
        LEFT JOIN `laender` AS `c` ON l.empfaengerland = c.iso";
    }
}
