<?php

namespace Acraviz\Http\Controllers;

use Acraviz\Support\Text;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiController extends BaseController
{
    public function save()
    {
        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container['request'];
        $package = $request->getUser();
        $token = $request->getPassword();
        if (Text::is($package, $token)) {
            /** @var $qb \Doctrine\DBAL\Query\QueryBuilder */
            $qb = $this->container['db']->createQueryBuilder();
            $result = $qb->select('id')
                ->from('applications')
                ->where($qb->expr()->eq('package_name', '?'))
                ->andWhere($qb->expr()->eq('token', '?'))
                ->setParameter(0, $package)
                ->setParameter(1, $token)
                ->execute();
            if ($result->rowCount() == 1) {
                $application = $result->fetchColumn(0);
                $post = $request->request->all();
                $data = array();
                foreach ($post as $key => $value) {
                    $data[strtolower($key)] = $value;
                }
                $data = array_replace($data, array(
                    'application_id' => $application,
                    'checksum' => hash('md5', $data['package_name'] . $data['stack_trace']),
                    'created_at' => date('Y-m-d H:i:s'),
                    'user_app_start_date' => $data['user_app_start_date'],
                    'user_crash_date' => $data['user_crash_date']
                ));
                $stacktrace = explode(PHP_EOL, $data['stack_trace']);
                foreach ($stacktrace as $line) {
                    if (strpos($line, 'Caused by: ') === 0) {
                        $data['exception'] = substr($line, strpos($line, ':') + 2);
                        break;
                    }
                }
                if (!isset($data['exception'])) {
                    $data['exception'] = substr($data['stack_trace'], 0, strpos($data['stack_trace'], ':'));
                }
                /** @var $db \Doctrine\DBAL\Connection */
                $db = $this->container['db'];
                return new Response('', $db->insert('reports', $data) == 1 ? 200 : 500);
            } else {
                throw new AccessDeniedHttpException();
            }
        } else {
            throw new BadRequestHttpException();
        }
    }
}
