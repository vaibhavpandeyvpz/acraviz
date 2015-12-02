<?php

namespace Acraviz\Http\Controllers;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;

class DeleteController extends BaseController
{
    public function applications()
    {
        return new Response('', $this->delete('applications'));
    }

    private function delete($table)
    {
        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container['request'];
        /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
        $qb = $this->container['db']->createQueryBuilder();
        $result = $qb->delete($table)
            ->where($qb->expr()->in('id', '?'))
            ->setParameter(0, $request->get('row_ids'), Connection::PARAM_INT_ARRAY)
            ->execute();
        return $result >= 1 ? 200 : 500;
    }

    public function reports()
    {
        return new Response('', $this->delete('reports'));
    }
}
