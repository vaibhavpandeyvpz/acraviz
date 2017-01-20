<?php

namespace Acraviz\Http\Controllers;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;

class HideController extends BaseController
{

    public function reports()
    {
        return new Response('', $this->hide('reports'));
    }

    public function showReports()
    {
        return new Response('', $this->show('reports'));
    }

    private function hide($table)
    {
        return $this->hideOrShow($table, true);
    }

    private function show($table)
    {
        return $this->hideOrShow($table, false);
    }

    private function hideOrShow($table, $hide) {
        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container['request'];
        /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
        $qb = $this->container['db']->createQueryBuilder();
        $result = $qb->update($table)
            ->set('hidden', ($hide) ? '1' : '0')
            ->where($qb->expr()->in('id', '?'))
            ->setParameter(0, $request->get('row_ids'), Connection::PARAM_INT_ARRAY)
            ->execute();
        return $result >= 1 ? 200 : 500;
    }
}
