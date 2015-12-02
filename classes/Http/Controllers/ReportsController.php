<?php

namespace Acraviz\Http\Controllers;

class ReportsController extends BaseController
{
    public function index()
    {
        /** @var $twig \Twig_Environment */
        $twig = $this->container['twig'];
        return $twig->render('reports.twig', array(
            'routes' => array(
                'data' => 'datatables_reports',
                'delete' => 'delete_reports',
                'view' => 'reports_view'
            ),
            'title' => 'titles.reports'
        ));
    }

    public function view($id)
    {
        /** @var $report \Doctrine\DBAL\Query\QueryBuilder */
        $report = $this->container['db']->createQueryBuilder();
        $report->select('*')
            ->from('reports')
            ->where($report->expr()->eq('id', '?'))
            ->setParameter(0, $id);
        $report = $report->execute()->fetch(\PDO::FETCH_ASSOC);
        /** @var $application \Doctrine\DBAL\Query\QueryBuilder */
        $application = $this->container['db']->createQueryBuilder();
        $application->select('*')
            ->from('applications')
            ->where($application->expr()->eq('id', '?'))
            ->setParameter(0, $report['application_id']);
        $application = $application->execute()->fetch(\PDO::FETCH_ASSOC);
        /** @var $twig \Twig_Environment */
        $twig = $this->container['twig'];
        return $twig->render('report.twig', array(
            'application' => $application,
            'report' => $report,
            'title' => 'titles.report'
        ));
    }
}
