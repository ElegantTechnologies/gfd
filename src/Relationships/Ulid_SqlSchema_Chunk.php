<?php

namespace Gfd\Relationships;

use EtFramework19\Db\Core\SqlSchema_ChunkBase;

class Ulid_SqlSchema_Chunk extends SqlSchema_ChunkBase
{
    public static function getArrSqlSchemaInnards(): array
    {
        return ['Ulid char(26)']; // 1/21' not sure this is used. See hack at SchoolTwistWip/src/Cfd/Cfdb/Core/CfdbBranchTrait.php::buildSqlCreate
    }
    public static function getArrColumnNames_PrimaryKey(): array
    {
        return ['Ulid'];
    }

    public static function getArrColumnNames_Indexes(): array
    {
        return ['Ulid'];
    }

}