<?php

namespace SchoolTwistWip\Cfd\Relationships;
use EtFramework19\Db\Core\SqlSchema_ChunkBase;

class Uuid_SqlSchema_Chunk extends SqlSchema_ChunkBase
{
    public static function getArrSqlSchemaInnards(): array
    {
        return ['Uuid CHAR(36)'];
    }
    public static function getArrColumnNames_PrimaryKey(): array
    {
        return ['Uuid'];
    }

    public static function getArrColumnNames_Indexes(): array
    {
        return ['Uuid'];
    }

}