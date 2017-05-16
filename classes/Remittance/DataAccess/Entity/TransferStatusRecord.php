<?php

namespace Remittance\DataAccess\Entity;


class TransferStatusRecord extends NamedEntity
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'transfer_status';

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'transfer_status_id';

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    protected $classname = self::class;

}
