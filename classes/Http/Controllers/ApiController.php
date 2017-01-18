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
                $data = $this->extractDataFromRequest($request);
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

    /**
     * Extracts only the passed data from the request
     * either in the JSON-format from the request body
     * or as form-urlencoded string from the post-request.
     *
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return array
     */
    private function extractDataFromRequest($request)
    {
        if ($request->getContentType() == 'json') {
            $source = json_decode($request->getContent(), true);
        } else {
            $source = $request->request->all();
        }
        $data = array();
        foreach ($source as $key => $value) {
            if (is_array($value)) {
                $value = $this->keyValueArrayToString($value);
            }
            $data[strtolower($key)] = $value;
        }
        return $data;
    }

    /**
     * Converts an array to a string with the
     * format <code><key>=<value>\n</code>.
     * <br><br>
     * If the value is an array itself the array
     * will be converted recursively the same way
     * with the original key as a prefix like
     * <code><key>.<sub-key>=<value>\n</code>.
     *
     * @param $keyValueArray
     * @param string $keyPrefix
     * @return string
     */
    private function keyValueArrayToString($keyValueArray, $keyPrefix = '')
    {
        $result = '';
        $first = true;
        foreach($keyValueArray as $key => $value) {
            if ($first) $first = false;
            else $result .= "\n"; // double quote necessary!
            if (is_array($value)) {
                $prefix = $this->prepend($keyPrefix, $key);
                $result .= $this->keyValueArrayToString($value, $prefix);
            } else {
                $result .= $this->prepend($keyPrefix, $key) . '=' . $value;
            }
        }
        return $result;
    }

    private function prepend($prefix, $string, $delimiter = '.')
    {
        if ($prefix === '') return $string;
        return $prefix . $delimiter . $string;
    }

}
