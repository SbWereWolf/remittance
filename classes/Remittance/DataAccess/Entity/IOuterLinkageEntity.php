<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.01.2017
 * Time: 22:28
 */

namespace Remittance\DataAccess\Entity;


interface IOuterLinkageEntity
{

    /** Удалить объект для внешней ссылки
     * @return bool успех выполнения
     */
    public function dropOuterLinkage():bool;

    /** Добавить объект для внешней ссылки
     * @return string идентификатор объекта для внешней ссылки
     */
    public function addOuterLinkage():string;
}
