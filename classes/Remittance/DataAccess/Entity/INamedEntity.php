<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:03
 */
namespace Remittance\DataAccess\Entity {
    /**
     * Интерфейс для работы с именнуемыми сущностями
     */
    interface INamedEntity
    {
        /** @var string колонка КОД */
        const CODE = 'code';
        /** @var string колонка НАИМЕНОВАНИЕ */
        const NAME = 'name';
        /** @var string колонка ОПИСАЕИЕ */
        const DESCRIPTION = 'description';


        /** Загрузить по коду записи
         * @param string $code код записи
         * @return bool успех выполнения
         */
        public function loadByCode(string $code):bool;

        /** Получить имя и описание записи
         * @param string $code значение ключа для свойства код
         * @param string $name значение ключа для свойства имя
         * @param string $description значение ключа для свойства описание
         * @return array массив с именем и описанием
         */
        public function getElementDescription(string $code = INamedEntity::CODE,
                                              string $name = INamedEntity::NAME,
                                              string $description = INamedEntity::DESCRIPTION):array;

    }
}
