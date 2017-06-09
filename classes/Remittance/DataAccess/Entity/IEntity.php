<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 17:58
 */
namespace Remittance\DataAccess\Entity {
    /**
     * Базовый интерфейс сущности
     */
    interface IEntity
    {
        /** Скрыть сущность
         * @return bool успех выполнения
         */
        public function hideEntity():bool;

        /** Добавить запись сущности в БД
         * @return bool успех выполнения
         */
        public function addEntity():bool;
    }
}
