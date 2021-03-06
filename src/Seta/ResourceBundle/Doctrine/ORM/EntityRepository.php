<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 13:47.
 */
namespace Seta\ResourceBundle\Doctrine\ORM;

use Doctrine\ORM\EntityRepository as BaseEntityRepository;

class EntityRepository extends BaseEntityRepository
{
    /**
     * Return a new instance of current entity.
     *
     * @return object
     * @codeCoverageIgnore
     */
    public function createNew()
    {
        $className = $this->getClassName();

        return new $className();
    }

    /**
     * Return QueryBuilder.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->createQueryBuilder($this->getAlias());
    }

    /**
     * Return Alias.
     *
     * @return string
     */
    protected function getAlias()
    {
        return 'o';
    }
}
