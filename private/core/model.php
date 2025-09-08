<?php

/**
 * Main Model
 */
class Model extends Database
{
    public $errors = array();

    function __construct()
    {
        if (!property_exists($this, 'table')) {
            $this->table = strtolower($this::class) . 's';
        }
    }

    public function findAll($limit = 0, $offset = 0, $rotations = "ASC")
    {
        $query = $limit > 0
            ? "SELECT * FROM $this->table ORDER BY id $rotations LIMIT $limit OFFSET $offset"
            : "SELECT * FROM $this->table ORDER BY id $rotations";

        $data = $this->query($query);

        return $this->applyAfterSelect($data);
    }

    public function findAllDistinct($query, $arr = array(), $limit = 0, $offset = 0, $rotations = "ASC")
    {
        if ($limit > 0) {
            $query .= " ORDER BY id $rotations LIMIT $limit OFFSET $offset";
        }

        return $this->applyAfterSelect($this->query($query, $arr));
    }

    public function selectCount()
    {
        return $this->query("SELECT COUNT(*) as numbers FROM $this->table");
    }

    public function selectCountWhere($column, $value, $name = "numbers")
    {
        $query = "SELECT COUNT(*) as {$name} FROM $this->table WHERE $column = :value";
        return $this->query($query, ['value' => $value]);
    }

    public function selctingId()
    {
        return $this->query("SELECT * FROM $this->table ORDER BY id DESC LIMIT 1");
    }

    public function selctingidColl($coll)
    {
        return $this->query("SELECT $coll FROM $this->table ORDER BY id DESC LIMIT 1");
    }

    public function selctingLastId()
    {
        return $this->query("SELECT * FROM $this->table ORDER BY id DESC LIMIT 1");
    }

    public function findSearch($querystrings, $value = array())
    {
        return $this->applyAfterSelect($this->query($querystrings, $value));
    }

    public function where($column, $value, $limit = 0, $offset = 0, $rotations = "ASC")
    {
        $column = addslashes($column);
        $query = $limit > 0
            ? "SELECT * FROM $this->table WHERE $column = :value ORDER BY id $rotations LIMIT $limit OFFSET $offset"
            : "SELECT * FROM $this->table WHERE $column = :value ORDER BY id $rotations";

        $data = $this->query($query, ['value' => $value]);
        return $this->applyAfterSelect($data);
    }

    public function where_query($query, $data)
    {
        return $this->applyAfterSelect($this->query($query, $data));
    }

    public function whereNot($column, $value, $limit = 0, $offset = 0, $rotations = "ASC")
    {
        $column = addslashes($column);
        $query = $limit > 0
            ? "SELECT * FROM $this->table WHERE $column != :value ORDER BY id $rotations LIMIT $limit OFFSET $offset"
            : "SELECT * FROM $this->table WHERE $column != :value ORDER BY id $rotations";

        $data = $this->query($query, ['value' => $value]);
        return $this->applyAfterSelect($data);
    }

    public function insert($data)
    {
        if (property_exists($this, 'allowedColumns')) {
            foreach ($data as $key => $column) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        if (property_exists($this, 'beforeInset')) {
            foreach ($this->beforeInset as $func) {
                $data = $this->$func($data);
            }
        }

        $keys = array_keys($data);
        $columns = implode(',', $keys);
        $values = implode(',:', $keys);

        $query = "INSERT INTO $this->table($columns) VALUES(:$values)";
        return $this->query($query, $data);
    }

    public function update($id, $data)
    {
        $str = "";
        foreach ($data as $key => $value) {
            $str .= "$key=:$key,";
        }
        $str = rtrim($str, ",");
        $data['id'] = $id;
        $query = "UPDATE $this->table SET $str WHERE id=:id";
        return $this->query($query, $data);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM $this->table WHERE id = :id", ['id' => $id]);
    }

    public function whereIn($column, $values = [])
    {
        if (empty($values)) return false;

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $query = "SELECT * FROM $this->table WHERE $column IN ($placeholders)";
        return $this->query($query, $values);
    }

    private function applyAfterSelect($data)
    {
        if (is_array($data) && !empty($data) && property_exists($this, 'afterSelect')) {
            foreach ($this->afterSelect as $func) {
                if (method_exists($this, $func)) {
                    $data = $this->$func($data);
                }
            }
        }
        return $data;
    }
}
