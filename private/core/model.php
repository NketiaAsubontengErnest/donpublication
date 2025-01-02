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
        if ($limit > 0) {
            $query = "SELECT * FROM $this->table ORDER BY id $rotations LIMIT $limit OFFSET $offset";
        } else {
            $query = "SELECT * FROM $this->table ORDER BY id $rotations";
        }
        $data = $this->query($query);

        //after select
        if (is_array($data)) {
            if (property_exists($this, 'afterSelect')) {
                foreach ($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
        }
        return $data;
    }

    public function findAllDistinct($query, $arr = array(), $limit = 0, $offset = 0, $rotations = "ASC")
    {
        if ($limit > 0) {
            $query = "$query ORDER BY id $rotations LIMIT $limit OFFSET $offset";
        }        

        $data = $this->query($query, $arr);

        //after select
        if (is_array($data)) {
            if (property_exists($this, 'afterSelect')) {
                foreach ($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
        }
        return $data;
    }
    public function selectCount()
    {
        $query = "SELECT COUNT(*) as numbers FROM $this->table";
        $data = $this->query($query);

        return $data;
    }
    public function selectCountWhere($column, $value, $name = "numbers")
    {
        $query = "SELECT COUNT(*) as {$name} FROM $this->table WHERE $column = :value";
        $data = $this->query($query, ['value' => $value]);

        return $data;
    }

    public function selctingId()
    {
        $query = "SELECT * FROM $this->table ORDER BY id DESC LIMIT 1";
        return $this->query($query);
    }

    public function selctingidColl($coll)
    {
        $query = "SELECT $coll FROM $this->table ORDER BY id DESC LIMIT 1";
        return $this->query($query);
    }

    public function selctingLastId()
    {
        $query = "SELECT * FROM $this->table ORDER BY id DESC LIMIT 1";
        return $this->query($query);
    }

    public function findSearch($querystrings, $value= array())
    {
        $data = $this->query($querystrings, $value);
        //after select
        if (is_array($data)) {
            if (property_exists($this, 'afterSelect')) {
                foreach ($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
        }
        return $data;
    }
    public function where($column, $value, $limit = 0, $offset = 0, $rotations = "ASC")
    {
        $column = addslashes($column);
        if ($limit > 0) {
            $query = "SELECT * FROM $this->table WHERE $column = :value ORDER BY id $rotations LIMIT $limit OFFSET $offset";
        } else {
            $query = "SELECT * FROM $this->table WHERE $column = :value ORDER BY id $rotations";
        }

        $data = $this->query($query, [
            'value' => $value
        ]);

        // //after select
        if (is_array($data)) {
            if (property_exists($this, 'afterSelect')) {
                foreach ($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
        }
        return $data;
    }

    public function where_query($query, $data)
    {
        $data = $this->query($query, $data);

        // //after select
        if (is_array($data)) {
            if (property_exists($this, 'afterSelect')) {
                foreach ($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
        }
        return $data;
    }

    public function whereNot($column, $value, $limit = 0, $offset = 0, $rotations = "ASC")
    {
        $column = addslashes($column);
        if ($limit > 0) {
            $query = "SELECT * FROM $this->table WHERE $column != :value  ORDER BY id $rotations LIMIT $limit OFFSET $offset";
        } else {
            $query = "SELECT * FROM $this->table WHERE $column != :value ORDER BY id $rotations";
        }

        $data = $this->query($query, [
            'value' => $value
        ]);

        // //after select
        if (is_array($data)) {
            if (property_exists($this, 'afterSelect')) {
                foreach ($this->afterSelect as $func) {
                    $data = $this->$func($data);
                }
            }
        }
        return $data;
    }

    public function insert($data)
    {

        //this remove unwanted columns
        if (property_exists($this, 'allowedColumns')) {
            foreach ($data as $key => $column) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        //run needed functions before insert
        if (property_exists($this, 'beforeInset')) {
            foreach ($this->beforeInset as $func) {
                $data = $this->$func($data);
            }
        }

        $keys = array_keys($data);
        $columns = implode(',', $keys);
        $values = implode(',:', $keys);

        $query = "INSERT  INTO $this->table($columns) VALUES(:$values)";
        return $this->query($query, $data);
    }

    public function update($id, $data)
    {
        $str = "";
        foreach ($data as $key => $value) {
            $str .= $key . "=:" . $key . ",";
        }
        $str = trim($str, ",");
        $data['id'] = $id;
        $query = "UPDATE $this->table SET $str WHERE id=:id";
        return $this->query($query, $data);
    }

    public function delete($id)
    {
        $data['id'] = $id;
        $query = "DELETE FROM $this->table WHERE id = :id";
        return $this->query($query, $data);
    }
}
