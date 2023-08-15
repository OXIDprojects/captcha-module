<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Shared;

use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\ConnectionProviderInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

trait Connection
{
    public function getQueryBuilder(): QueryBuilder
    {
        $container = ContainerFactory::getInstance()->getContainer();

        return $container->get(QueryBuilderFactoryInterface::class)->create();
    }

    public function getDb(): \Doctrine\DBAL\Connection
    {
        return ContainerFactory::getInstance()->getContainer()->get(ConnectionProviderInterface::class)->get();
    }
}
