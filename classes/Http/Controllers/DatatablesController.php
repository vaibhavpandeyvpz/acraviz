<?php

namespace Acraviz\Http\Controllers;

use Acraviz\Support\Datatables;
use Symfony\Component\HttpFoundation\JsonResponse;

class DatatablesController extends BaseController
{
    public function applications()
    {
        /** @var $join \Doctrine\DBAL\Query\QueryBuilder */
        $join = $this->container['db']->createQueryBuilder();
        $join->select('application_id', 'COUNT(application_id) AS count')
            ->from('reports');
        /** @var $select \Doctrine\DBAL\Query\QueryBuilder */
        $select = $this->container['db']->createQueryBuilder();
        $select->select('A.id', 'C.count AS crash_count', 'A.package_name', 'A.title', 'A.token', 'A.created_at')
            ->from('applications', 'A')
            ->leftJoin('A', '(' . $join->getSQL() . ')', 'C', 'C.application_id = A.id');
        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container['request'];
        $data = Datatables::of($select)->make($request->request->all());
        return new JsonResponse($data);
    }

    public function reports()
    {
        /** @var $join \Doctrine\DBAL\Query\QueryBuilder */
        $join = $this->container['db']->createQueryBuilder();
        $join->select('checksum', 'COUNT(checksum) AS count')
            ->from('reports')
            ->groupBy('checksum');
        /** @var $select \Doctrine\DBAL\Query\QueryBuilder */
        $select = $this->container['db']->createQueryBuilder();
        $select->select('R.id', 'R.android_version', 'R.app_version_name', 'R.app_version_code', 'R.brand', 'C.count AS crash_count', 'R.exception', 'R.package_name', 'R.phone_model', 'A.title', 'R.created_at')
            ->from('reports', 'R')
            ->leftJoin('R', 'applications', 'A', 'A.id = R.application_id')
            ->innerJoin('R', '(' . $join->getSQL() . ')', 'C', 'C.checksum = R.checksum');
        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container['request'];
        $data = Datatables::of($select)->make($request->request->all());
        return new JsonResponse($data);
    }
}
