<?php
require_once 'FrontController.php';

class dataSource extends FrontController
{
    private $driver;

    private function conection()
    {
        try {
            $this->driver = $this->config();
            $conexion = new PDO(
                "{$this->driver[0]}:host={$this->driver[1]};dbname={$this->driver[2]};port={$this->driver[3]}",
                $this->driver[4],
                $this->driver[5]
            );
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conexion->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            return $conexion;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }

    }

    private function query(string $sql, array $params = null) : array
    {
        $conec = $this->conection();
        $stm = $conec->prepare($sql);
        $stm->execute($params);
        $result = $stm->fetchAll();
        return $result;
    }

    private function execute(string $sql, array $params = null)
    {
        $conec = $this->conection();
        $stm = $conec->prepare($sql);
        $stm->execute($params);
        $result = $stm->rowCount();
        return $result;
    }

    protected function getTable(array $colums, string $table, array $id = null)
    {
        $sql = "SELECT ";
        for ($i = 0; $i < count($colums); $i++) {
            if ($i < (count($colums) - 1)) {
                $sql .= $colums[$i] . ", ";
            } else {
                $sql .= $colums[$i];
            }
        }
        if (count($id) > 0) {
            foreach ($id as $key => $value) {
                $sql .= " FROM {$table} WHERE {$key}=:{$key}";
                $answer = $this->query($sql, array(":{$key}" => $value));
            }
        } else {
            $sql .= " FROM {$table}";
            $answer = $this->query($sql);
        }
        return $answer;
    }

    protected function addTable(array $colums, string $table)
    {
        $sql = "INSERT INTO {$table} (";
        $tmp = count($colums);
        $i = 1;
        foreach ($colums as $key => $value) {
            if ($i < $tmp) {
                $sql .= "{$key}, ";
                $i++;
            } else {
                $sql .= "{$key}) ";
                $i = 1;
            }
        }
        $sql .= "VALUES (";
        foreach ($colums as $key => $value) {
            if ($i < $tmp) {
                $sql .= ":{$key}, ";
                $i++;
            } else {
                $sql .= ":{$key}) ";
                $i = 1;
            }
        }
        $answer = $this->execute($sql, $colums);
        return $answer;
    }

    protected function updateTable(array $colums, string $table, array $where)
    {
        $sql = "UPDATE {$table} SET ";
        $tmp = count($colums);
        $tmp_where = count($where);
        $i = 1;
        foreach ($colums as $key => $value) {
            if ($i < $tmp) {
                $sql .= "{$key}= :{$key}, ";
                $i++;
            } else {
                $sql .= "{$key}= :{$key} ";
                $i = 1;
            }
        }
        if (count($where) == 1) {
            foreach ($where as $key => $value) {
                $sql .= " WHERE {$key} = :{$key}";
            }
        } else {
            foreach ($colums as $key => $value) {
                if ($i < $tmp_where) {
                    $sql .= " WHERE {$key}= :{$key}";
                } else {
                    $sql .= " AND {$key}= :{$key} ";
                }
            }
        }
        $a = array_merge($colums, $where);
        $answer = $this->query($sql, $a);
        return $answer;
    }

    protected function trashTable(array $id, string $table)
    {
        foreach ($id as $key => $value) {
            $sql = "UPDATE {$table} SET  deleted_at=NOW() WHERE {$key}= :{$key}";
            $answer = $this->execute($sql, array(":{$key}" => $value));
        }
        return $answer;
    }

    protected function deleteTable(array $id, string $table)
    {
        $sql = "DELETE FROM {$table} WHERE ";
        foreach ($id as $key => $value) {
            $sql .= "{$key}= :{$key}";
            $answer = $this->execute($sql, array(":{$key}" => $value));
        }
        return $answer;
    }
}