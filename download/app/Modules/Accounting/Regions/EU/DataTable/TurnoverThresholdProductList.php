<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\DataTable;

class TurnoverThresholdProductList
{
    public static function getTableDefinition(bool $notUsable): array
    {
        $yes = __('yes');
        $format = 'de_DE';
        $tableDefinitions = [];
        $tableDefinitions['allowed']['lieferschwelle'] = ['artikel'];

        $tableDefinitions['heading'] = [
            __('Artikel-Nr.'),
            __('Artikelname'),
            __('Empfängerland'),
            __('Steuersatz'),
            __('Bemerkung'),
            __('Erlöskonto'),
            __('Aktiv'),
            '',
            __('Menü'),
        ];
        $tableDefinitions['width'] = ['15%', '15%', '15%', '10%', '20%', '5%', '5%', '1%', '1%'];

        $tableDefinitions['findcols'] = [
            'a.nummer',
            'a.name_de',
            'l.empfaengerland',
            'l.steuersatz',
            'l.bemerkung',
            'l.revenue_account',
            "IF(l.aktiv, '{$yes}', '-')",
            'l.id',
            'l.id',
        ];
        $tableDefinitions['searchsql'] = [
            'a.nummer',
            'a.name_de',
            'l.empfaengerland',
            "FORMAT(l.steuersatz, {$format})",
            'l.bemerkung',
            'l.revenue_account',
            "IF(l.aktiv, '{$yes}', '-')",
        ];

        $tableDefinitions['defaultorder'] = 1;
        $tableDefinitions['defaultorderdesc'] = 0;
        $tableDefinitions['trcol'] = 7;
        $tableDefinitions['alignright'] = [4];
        $tableDefinitions['numbercols'] = [3];

        $tableDefinitions['menu'] = trim(view('modules.turnover_threshold.product_menu_column')->render());

        $tableDefinitions['where'] = 'l.id > 0';
        if ($notUsable) {
            $tableDefinitions['where'] = ' ISNULL(ls.empfaengerland) ';
        }

        $tableDefinitions['sql'] = self::getSqlColumn($format, $yes);

        $tableDefinitions['fastcount'] = self::getFastCountColumn();

        $tableDefinitions['count'] = 'SELECT COUNT(l.id) FROM `lieferschwelle_artikel` AS `l`';

        return $tableDefinitions;
    }

    private static function getFastCountColumn(): string
    {
        return 'SELECT COUNT(l.id)
        FROM `lieferschwelle_artikel` AS `l` 
        LEFT JOIN 
        (
          SELECT `empfaengerland` 
          FROM  `lieferschwelle`
          GROUP BY `empfaengerland`
        ) AS `ls` ON l.empfaengerland = ls.empfaengerland
        LEFT JOIN `artikel` AS `a` ON l.artikel = a.id';
    }

    private static function getSqlColumn(string $format, string $yes): string
    {
        return "SELECT l.id,
            a.nummer,
            a.name_de,
            l.empfaengerland, 
            FORMAT(l.steuersatz, 2, '{$format}'), 
            l.bemerkung,
            l.revenue_account,
            IF(l.aktiv, '{$yes}', '-'),
            IF(ISNULL(ls.empfaengerland), '#F1B19F', ''),
            l.id 
        FROM `lieferschwelle_artikel` AS `l` 
        LEFT JOIN 
        (
          SELECT `empfaengerland` 
          FROM `lieferschwelle`
          GROUP BY `empfaengerland`
        ) AS `ls` ON l.empfaengerland = ls.empfaengerland
        LEFT JOIN `artikel` AS `a` ON l.artikel = a.id";
    }
}
