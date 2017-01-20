<?php

namespace Acraviz\Http\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
        $qb = $this->container['db']->createQueryBuilder();
        $qb->select('A.title', 'A.package_name', 'R.checksum', 'R.created_at AS last_crashed_on')
            ->from('reports', 'R')
            ->leftJoin('R', 'applications', 'A', 'A.id = R.application_id')
            ->where($qb->expr()->eq('hidden', '0'))
            ->orderBy('last_crashed_on', 'DESC');
        /** @var $group \Doctrine\DBAL\Query\QueryBuilder */
        $group = $this->container['db']->createQueryBuilder();;
        $group->select('*', 'COUNT(*) AS crash_count')
            ->from('(' . $qb->getSQL() . ')', 'T')
            ->groupBy('checksum')
            ->orderBy('crash_count', 'DESC');
        $crashing = $group->execute();
        /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
        $qb = $this->container['db']->createQueryBuilder();
        $qb->select('exception', 'checksum', 'created_at AS last_reported_on')
            ->from('reports')
            ->where($qb->expr()->eq('hidden', '0'))
            ->orderBy('last_reported_on', 'DESC');
        /** @var $group \Doctrine\DBAL\Query\QueryBuilder */
        $group = $this->container['db']->createQueryBuilder();;
        $group->select('*', 'COUNT(*) AS report_count')
            ->from('(' . $qb->getSQL() . ')', 'T')
            ->groupBy('checksum')
            ->orderBy('report_count', 'DESC');
        $reported = $group->execute();
        /** @var $twig \Twig_Environment */
        $twig = $this->container['twig'];
        return $twig->render('dashboard.twig', array(
            'most_crashing' => $crashing->fetchAll(\PDO::FETCH_ASSOC),
            'most_reported' => $reported->fetchAll(\PDO::FETCH_ASSOC),
            'title' => 'titles.dashboard'
        ));
    }
}
