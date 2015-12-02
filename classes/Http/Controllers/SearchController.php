<?php

namespace Acraviz\Http\Controllers;

use Acraviz\Support\Text;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SearchController extends BaseController
{
    public function query($query)
    {
        if (Text::is($query)) {
            /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
            $qb = $this->container['db']->createQueryBuilder();;
            $qb->select('R.id', 'A.title as application', 'R.checksum', 'R.exception', 'R.created_at')
                ->from('reports', 'R')
                ->leftJoin('R', 'applications', 'A', 'A.id = R.application_id')
                ->where($qb->expr()->like('R.stack_trace', ':query'))
                ->orWhere($qb->expr()->like('A.title', ':query'))
                ->orderBy('R.created_at', 'DESC');
            /** @var $group \Doctrine\DBAL\Query\QueryBuilder */
            $group = $this->container['db']->createQueryBuilder();;
            $group->select('*')
                ->from('(' . $qb->getSQL() . ')', 'T')
                ->groupBy('checksum')
                ->setParameter('query', "%{$query}%");
            $result = $group->execute();
            $results = array();
            if ($result->rowCount() >= 1) {
                /** @var $url \Symfony\Component\Routing\Generator\UrlGeneratorInterface */
                $url = $this->container['url_generator'];
                while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                    $results[] = array(
                        'application' => $row['application'],
                        'datetime' => $row['created_at'],
                        'exception' => $row['exception'],
                        'url' => $url->generate('reports_view', array('id' => $row['id']))
                    );
                }
            }
            return new JsonResponse($results);
        } else {
            throw new BadRequestHttpException();
        }
    }
}
