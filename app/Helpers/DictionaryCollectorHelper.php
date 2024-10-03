<?php

namespace App\Helpers;

class DictionaryCollectorHelper
{
    const KRON_PACKAGE_TYPE = 1;

    const KRON_PROJECT_ID = 1;

    const KRON_DATABASE_NAME = 'KRON_DEV';

    const KRON_TM_PACKAGE_TYPE = 1;

    const KRON_TM_PROJECT_ID = 20;

    const KRON_TM_DATABASE_NAME = 'KRON_TM_DEV';

    const KRON_REF_DATABASE = 'KRON_REF';

    const KRON_TM_REF_DATABASE = 'KRON_TM_REF';

    public static function formatValue($value, $dataType = null): float|int|string
    {
        if ($value === '') {
            return "''";
        } elseif (is_null($value)) {
            return 'NULL';
        }

        return match ($dataType) {
            'tinyint', 'smallint', 'int', 'bigint' => (int) $value,
            'decimal', 'numeric', 'money', 'smallmoney' => (float) $value,
            'float', 'real' => (float) $value,
            'date', 'datetime', 'datetime2', 'datetimeoffset', 'smalldatetime', 'time' => "CONVERT(datetime, '$value', 121)",
            'binary', 'varbinary', 'image' => '0x'.bin2hex($value),
            'char', 'text', 'varchar' => "'$value'",
            'nchar', 'ntext', 'nvarchar' => "N'$value'",
            default => $value,
        };
    }

    public static function getTables(string $databaseName): array
    {
        return \DB::connection($databaseName)
            ->select("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
    }
}
