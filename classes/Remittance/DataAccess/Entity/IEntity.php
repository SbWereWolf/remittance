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
        /** @var string колонка признака "является скрытым" */
        const IS_HIDDEN = 'is_hidden';

        /** @var string значение для поднятого флага "является скрытым" */
        const DEFINE_AS_HIDDEN = true;
        /** @var string значение для поднятого флага "является скрытым" */
        const DEFINE_AS_NOT_HIDDEN = false;
        /** @var string значение по умолчанию для признака "является скрытым" */
        const DEFAULT_IS_HIDDEN = self::DEFINE_AS_NOT_HIDDEN;

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
