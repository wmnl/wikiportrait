<?php

/**
 * Compares etc/scheme.sql with the live database and builds the statements to bring them in line.
 * Both sides are parsed from CREATE TABLE statements (the dump and SHOW CREATE TABLE share the format).
 */

function schemaFile(): ?string
{
    $candidates = defined('SCHEMA_FILE') ? [SCHEMA_FILE] : [dirname(ABSPATH) . '/etc/scheme.sql'];

    foreach ($candidates as $candidate) {
        if (is_readable($candidate)) {
            return $candidate;
        }
    }

    return null;
}

function parseCreateTables(string $sql): array
{
    preg_match_all('/CREATE TABLE `(\w+)` \((.*?)\n\)([^;\n]*)/s', $sql, $matches, PREG_SET_ORDER);

    $tables = [];
    foreach ($matches as [, $name, $body, $options]) {
        $table = [
            'sql'     => "CREATE TABLE `{$name}` ({$body}\n)" . preg_replace('/ AUTO_INCREMENT=\d+/', '', $options),
            'columns' => [],
            'keys'    => [],
        ];

        foreach (explode("\n", trim($body)) as $line) {
            $line = rtrim(trim($line), ',');
            if (preg_match('/^`(\w+)` /', $line, $m)) {
                $table['columns'][$m[1]] = $line;
            } elseif (str_starts_with($line, 'PRIMARY KEY')) {
                $table['keys']['PRIMARY KEY'] = $line;
            } elseif (preg_match('/^(?:(?:UNIQUE|FULLTEXT|SPATIAL) )?KEY `(\w+)`/', $line, $m)) {
                // A foreign key and its index usually share a name, so keep them apart
                $table['keys']["Index `{$m[1]}`"] = $line;
            } elseif (preg_match('/^CONSTRAINT `(\w+)`/', $line, $m)) {
                $table['keys']["Constraint `{$m[1]}`"] = $line;
            }
        }

        $tables[$name] = $table;
    }

    return $tables;
}

function currentTables(): array
{
    $sql = '';
    foreach (DB::queryFirstColumn('SHOW TABLES') as $name) {
        $sql .= DB::queryFirstRow("SHOW CREATE TABLE `{$name}`")['Create Table'] . ";\n";
    }

    return parseCreateTables($sql);
}

function sameDefinition(string $a, string $b): bool
{
    return preg_replace('/\s+/', ' ', $a) === preg_replace('/\s+/', ' ', $b);
}

function dropKeyClause(string $definition): string
{
    if (str_starts_with($definition, 'PRIMARY KEY')) {
        return 'DROP PRIMARY KEY';
    }

    if (preg_match('/^CONSTRAINT `(\w+)` (FOREIGN KEY)?/', $definition, $m)) {
        return isset($m[2]) ? "DROP FOREIGN KEY `{$m[1]}`" : "DROP CONSTRAINT `{$m[1]}`";
    }

    preg_match('/KEY `(\w+)`/', $definition, $m);

    return "DROP KEY `{$m[1]}`";
}

function columnPosition(array $wantedColumns, array $currentColumns, string $column): string
{
    $previous = 'FIRST';
    foreach (array_keys($wantedColumns) as $name) {
        if ($name === $column) {
            break;
        }
        if (isset($currentColumns[$name])) {
            $previous = "AFTER `{$name}`";
        }
    }

    return $previous;
}

/**
 * Each difference has a stable id, so it can be looked up again after other changes were applied.
 * 'sql' is null for differences that are only reported.
 */
function schemaDifferences(array $wanted, array $current): array
{
    $diffs = [];
    $add   = function (
        string $id,
        string $table,
        string $description,
        ?string $sql,
        bool $removes = false,
        ?string $now = null
    ) use (&$diffs) {
        $diffs[] = compact('id', 'table', 'description', 'sql', 'removes', 'now');
    };

    foreach ($wanted as $table => $definition) {
        if (!isset($current[$table])) {
            $add("table:{$table}", $table, t('schema.table_missing'), $definition['sql']);
        }
    }

    foreach ($wanted as $table => $definition) {
        if (!isset($current[$table])) {
            continue;
        }
        $live = $current[$table];

        // Order: new columns, then indexes (they may need new columns), then changed columns
        // (an AUTO_INCREMENT column needs its index), then removals
        foreach ($definition['columns'] as $column => $line) {
            if (!isset($live['columns'][$column])) {
                $position = columnPosition($definition['columns'], $live['columns'], $column);
                $add(
                    "column:{$table}.{$column}",
                    $table,
                    t('schema.column_missing', ['column' => $column]),
                    "ALTER TABLE `{$table}` ADD COLUMN {$line} {$position}"
                );
            }
        }

        foreach ($definition['keys'] as $key => $line) {
            if (!isset($live['keys'][$key])) {
                $add("key:{$table}.{$key}", $table, t('schema.key_missing', ['key' => $key]), "ALTER TABLE `{$table}` ADD {$line}");
            } elseif (!sameDefinition($line, $live['keys'][$key])) {
                $add(
                    "key:{$table}.{$key}",
                    $table,
                    t('schema.key_differs', ['key' => $key]),
                    "ALTER TABLE `{$table}` " . dropKeyClause($live['keys'][$key]) . ", ADD {$line}",
                    false,
                    $live['keys'][$key]
                );
            }
        }

        foreach ($definition['columns'] as $column => $line) {
            if (isset($live['columns'][$column]) && !sameDefinition($line, $live['columns'][$column])) {
                $add(
                    "column:{$table}.{$column}",
                    $table,
                    t('schema.column_differs', ['column' => $column]),
                    "ALTER TABLE `{$table}` MODIFY COLUMN {$line}",
                    false,
                    $live['columns'][$column]
                );
            }
        }

        foreach ($live['keys'] as $key => $line) {
            if (!isset($definition['keys'][$key])) {
                $add(
                    "key:{$table}.{$key}",
                    $table,
                    t('schema.key_extra', ['key' => $key]),
                    "ALTER TABLE `{$table}` " . dropKeyClause($line),
                    true,
                    $line
                );
            }
        }

        foreach ($live['columns'] as $column => $line) {
            if (!isset($definition['columns'][$column])) {
                $add(
                    "column:{$table}.{$column}",
                    $table,
                    t('schema.column_extra', ['column' => $column]),
                    "ALTER TABLE `{$table}` DROP COLUMN `{$column}`",
                    true,
                    $line
                );
            }
        }
    }

    foreach (array_diff_key($current, $wanted) as $table => $definition) {
        $add("table:{$table}", $table, t('schema.table_extra'), null);
    }

    return $diffs;
}

/**
 * Applies the selected differences in the order they are listed. The differences are recalculated
 * before every statement, so column positions take earlier changes into account.
 */
function applySchemaDifferences(array $wanted, array $ids): array
{
    $results = [];

    DB::query('SET FOREIGN_KEY_CHECKS = 0');
    foreach (array_column(schemaDifferences($wanted, currentTables()), 'id') as $id) {
        if (!in_array($id, $ids, true)) {
            continue;
        }

        $diff = array_values(array_filter(
            schemaDifferences($wanted, currentTables()),
            fn ($d) => $d['id'] === $id && $d['sql'] !== null
        ))[0] ?? null;

        if (!$diff) {
            continue;
        }

        try {
            DB::query($diff['sql']);
            $results[] = ['success' => true, 'sql' => $diff['sql'], 'message' => t('schema.applied')];
        } catch (Exception $e) {
            $results[] = ['success' => false, 'sql' => $diff['sql'], 'message' => $e->getMessage()];
        }
    }
    DB::query('SET FOREIGN_KEY_CHECKS = 1');

    return $results;
}
