<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Database Query Builder
 *
 *  Is Responsible For Converting The Parameters Passed To Proper Sql Strings,
 *  Where|Update Sql Strings, And Prepared Statement Placeholders.
 *
 */

namespace TEMP\Database;

class QueryBuilder {


    /**
     *  Build Select Sql Query String
     *
     *  @see                            $this->sql() Comments
     */
    public function sqlSelect($column, $table, $where, $rules = '') {
        return $this->sql('select', 'SELECT ' . $column . ' FROM ' . $table,  $where, $rules);
    }


    /**
     *  Build Insert Sql Query String
     *
     *  @see                            $this->sql() Comments
     */
    public function sqlInsert($table, $data) {
        $keys = array_keys((array) $data);

        return 'INSERT INTO ' . $table . '(`' . implode($keys, '`, `') . '`) VALUES(:' . implode($keys, ', :') . ')';
    }


    /**
     *  Build Update Sql Query String
     *
     *  @see                            $this->sql() Comments
     */
    public function sqlUpdate($table, $data, $where = [], $rules = '') {
        return $this->sql('update', 'UPDATE ' . $table . ' SET ' . $this->clause($data, 'update'), $where, $rules);
    }


    /**
     *  Build Delete Sql Query String
     *
     *  @see                            $this->sql() Comments
     */
    public function sqlDelete($table, $where = [], $rules = '') {
        return $this->sql('delete', 'DELETE FROM ' . $table . ' WHERE', $where, $rules);
    }


    /**
     *  Build Sql String For Delete, Select, And Update Methods
     *
     *  @param  string $type            Type Of Sql String Being Created
     *  @param  string $sql             Sql String
     *  @param  array  $where           ['Columm' => 'Data']
     *  @param  string $rules           Additional Sql Rules To Set
     *  @return string                  Sql String
     */
    private function sql($type, $sql = '', $where = [], $rules = '') {
        if (in_array($type, ['select', 'update']) && ($where || $rules)) {
            $sql .= ' WHERE';
        }
        $sql .= $where  ? ' ' . $this->clause($where)   : '';
        $sql .= $rules  ? ' ' . $rules                  : '';
        return $sql;
    }


    /**
     *  Build Clause For Main Sql Function
     *
     *  @param  array  $array           Array Used To Build Update || Where Clause
     *  @param  string $type            Type Of Clause To Create
     *  @return string                  Paramatized SQL Clause With Placeholders
     */
    private function clause($array, $type = 'where') {
        $columns    = array_keys((array) $array);
        $count      = count($columns);

        $prefix     = $type == 'update' ? ', ' : ($type == 'where' ? ' AND ' : '');
        $sql        = '';

        for ($i = 0; $i < $count; ++$i) {
            $key    = $columns[$i];
            $sql   .= ($i > 0 ? $prefix : '') . '`' . str_replace('.', '`.`', $key) . '`' . ' = :' . str_replace('.', '', $key);
        }
        return $sql;
    }


    /**
     *  Build Placeholders for Main Functions
     *
     *  @param  array $data             Assoc Array Used To Build Placeholder Array
     *  @return array                   Assoc Array Containing Placeholder + Data
     */
    public function placeholder($data = []) {
        foreach ((array) $data as $key => $value) {
            $output[':' . str_replace('.', '', $key)] = $value;
        }
        return isset($output) ? $output : [];
    }
}
