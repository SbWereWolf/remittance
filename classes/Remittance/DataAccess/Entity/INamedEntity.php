<?php

namespace Remittance\DataAccess\Entity {
    /**
     * Интерфейс для работы с именнуемыми сущностями
     */
    interface INamedEntity
    {
        /** @var string колонка КОД */
        const CODE = 'code';
        /** @var string колонка НАИМЕНОВАНИЕ */
        const TITLE = 'title';
        /** @var string колонка ОПИСАЕИЕ */
        const DESCRIPTION = 'description';

        /** Получить имя и описание записи
         * @param string $code значение ключа для свойства код
         * @param string $title значение ключа для свойства имя
         * @param string $description значение ключа для свойства описание
         * @return array массив с именем и описанием
         */
        public function getElementDescription(string $code = INamedEntity::CODE,
                                              string $title = INamedEntity::TITLE,
                                              string $description = INamedEntity::DESCRIPTION):array;

    }
}
