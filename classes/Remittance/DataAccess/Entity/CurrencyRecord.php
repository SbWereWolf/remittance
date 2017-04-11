<?php

namespace Remittance\DataAccess\Entity;


use Remittance\Core\Common;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;


class CurrencyRecord extends NamedEntity
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'currency';

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'currency_id';

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    protected $classname = self::class;

}
