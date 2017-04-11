<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2017
 * Time: 1:23
 */

namespace Remittance\DataAccess\Entity;


interface IPrimitiveData
{

    /** Обновляет (изменяет) запись в БД
     * @return bool успешность изменения
     */
    public function mutateEntity():bool;

    /** Установить свойства экземпляра в соответствии со значениями
     * @param array $namedValue массив значений
     * @return bool успех выполнения
     */
    public function setByNamedValue(array $namedValue):bool;
}
