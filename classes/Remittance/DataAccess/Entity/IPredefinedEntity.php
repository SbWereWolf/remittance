<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2017
 * Time: 1:17
 */

namespace Remittance\DataAccess\Entity;


interface IPredefinedEntity
{

    /** Добавить дочернюю сущность
     * @return bool успех выполнения
     */
    public function addPredefinedEntity():bool;

}
