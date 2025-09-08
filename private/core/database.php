<?php

/**
 * Database Connection
 */
class Database
{
    private function connect()
    {
        $string = DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME;
        try {
            return new PDO($string, DBUSER, DBPASS);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query($query, $data = array(), $data_type = "object")
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        if ($stm) {
            if (!is_array($data)) {
                $data = [];
            }

            $check = $stm->execute($data);

            if ($check) {
                $result = ($data_type == "object") ? $stm->fetchAll(PDO::FETCH_OBJ) : $stm->fetchAll(PDO::FETCH_ASSOC);
                if (is_array($result) && count($result) > 0) {
                    return $result;
                }
            }
        }

        return false;
    }
}
