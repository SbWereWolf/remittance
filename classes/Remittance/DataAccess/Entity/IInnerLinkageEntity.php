<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Remittance\DataAccess\Entity {
    /**
     * Интерфейс чтения сущности из БД
     */
    interface IInnerLinkageEntity
    {
        /** Удалить стыковку по внешнему ключу правой таблицы
         * @param string $leftId внешний ключ левой таблицы
         * @param string $rightId внешний ключ правой таблицы
         * @return bool успех выполнения
         */
        public function dropLinkageByBoth(string $leftId, string $rightId):bool;

        /** Удалить стыковку по внешнему ключу правой таблицы
         * @param string $id внешней ключ правой таблицы
         * @return bool успех выполнения
         */
        public function dropLinkageByRight(string $id):bool;

        /** Удалить стыковку по внешнему ключу левой таблицы
         * @param string $id внешней ключ левой таблицы
         * @return bool успех выполнения
         */
        public function dropLinkageByLeft(string $id):bool;

        /** Добавить запись в БД с заданной внутренней ссылкой
         * @param string $leftId внешний ключ левой таблицы
         * @param string $rightId внешний ключ правой таблицы
         * @return bool успех выполнения
         */
        public function addInnerLinkage(string $leftId, string $rightId):bool;

        /** Загрузить значения по ссылке на правую таблицу
         * @param string $rightId внешний ключ правой таблицы
         * @return bool успех выполнения
         */
        public function loadByRight(string $rightId):bool;

        /** Загрузить значения по ссылке на левую таблицу
         * @param string $leftId внешний ключ левой таблицы
         * @return bool успех выполнения
         */
        public function loadByLeft(string $leftId):bool;

        /** Установить свойства экземпляра в соответствии со значениями
         * @param array $namedValue массив значений
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue):bool;
    }
}
