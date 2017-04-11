<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.2017
 * Time: 17:32
 */

namespace Remittance\DataAccess\Entity;


use Remittance\Core\ICommon;

class Record implements IRecord
{
    /** @var string имя таблицы БД для хранения записи */
    const TABLE_NAME = 'record_table';

    /** @var string идентификатор записи */
    public $id = ICommon::EMPTY_VALUE;
    /** @var string имя таблицы БД для хранения записи */
    protected $tablename = self::TABLE_NAME;
}
