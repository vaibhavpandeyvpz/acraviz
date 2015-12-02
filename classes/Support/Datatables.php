<?php

namespace Acraviz\Support;

use Doctrine\DBAL\Query\QueryBuilder;

class Datatables
{
    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * @param QueryBuilder $query
     */
    private function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $request
     * @return array
     */
    public function make(array $request)
    {
        $output = [
            'data' => [],
            'draw' => $request['draw'],
            'recordsFiltered' => 0,
            'recordsTotal' => 0
        ];
        /**
         * Order By
         */
        if (isset($request['order'])) {
            for ($i = 0; $i < count($request['order']); ++$i) {
                $j = intval($request['order'][$i]['column']);
                if ($request['columns'][$j]['orderable'] != 'true') {
                    continue;
                }
                $column = $request['columns'][$j]['data'];
                $sort = $request['order'][$i]['dir'];
                $this->query->addOrderBy($column, $sort);
            }
        }
        /**
         * Count All
         */
        $temp = clone $this->query;
        $temp->resetQueryPart('select');
        $temp->resetQueryPart('orderBy');
        $temp->select("COUNT(*)");
        $output['recordsTotal'] = $temp->execute()->fetchColumn(0);
        /**
         * Filter
         */
        for ($i = 0; $i < count($request['columns']); ++$i) {
            if ($request['columns'][$i]['searchable'] != 'true') {
                continue;
            }
            $value = $request['columns'][$i]['search']['value'];
            if (strlen($value) > 0) {
                $column = $request['columns'][$i]['data'];
                $value = $this->query->getConnection()->quote("{$value}%");
                $this->query->andHaving($this->query->expr()->like($column, $value));
            }
        }
        /**
         * Search
         */
        if (isset($request['search'])) {
            $value = $request['search']['value'];
            if (strlen($value) > 0) {
                for ($i = 0; $i < count($request['columns']); ++$i) {
                    if ($request['columns'][$i]['searchable'] != 'true') {
                        continue;
                    }
                    $column = $request['columns'][$i]['data'];
                    $this->query->orHaving($this->query->expr()->like($column, ':search'));
                }
                $this->query->setParameter('search', "%{$value}%");
            }
        }
        /**
         * Count Filtered
         */
        $temp = clone $this->query;
        $temp->resetQueryPart('orderBy');
        $output['recordsFiltered'] = $temp->execute()->rowCount();
        /**
         * Limit
         */
        if (isset($request['start'])) {
            $this->query->setFirstResult($request['start']);
        }
        if (isset($request['length'])) {
            $this->query->setMaxResults($request['length']);
        }
        /**
         * Fetch Results
         */
        $output['data'] = $this->query->execute()->fetchAll(\PDO::FETCH_ASSOC);
        /**
         * Add Filter
         */
        return $output;
    }

    /**
     * @param QueryBuilder $query
     * @return static
     */
    public static function of(QueryBuilder $query)
    {
        return new static($query);
    }
}
