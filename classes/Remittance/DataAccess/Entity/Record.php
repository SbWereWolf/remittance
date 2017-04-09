<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.2017
 * Time: 17:32
 */

namespace Remittance\DataAccess\Entity;


class Record
{
    /** @var string колонка для идентификатора */
    const ID = 'id';

    /** @var string имя таблицы БД для хранения записи */
    const TABLE_NAME = 'record_table';
    /** @var string идентификатор записи */
    public $id = 0;
    /** @var string имя таблицы БД для хранения записи */
    protected $tablename = self::TABLE_NAME;
}
