<?php
require_once "database.php";
class RecoveryDatabase extends Database
{
    var $disableForeignKeyChecks;
    var $batchSize;
    var $backupDir;
    var $backupFile;
    public function __construct()
    {

        $this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
        $this->batchSize = defined('BATCH_SIZE') ? BATCH_SIZE : 1000;
    }
    public function backupDatabase()
    {
        $this->backupDir = BACKUP_DIR ? BACKUP_DIR : '.';
        $this->backupFile = 'backupBaseDatos-' . DB_NAME . '-' . date("Ymd_His", time()) . '.sql';

        try {
            $tables = array();
            $conn = self::connect();


            $query = "SHOW TABLES";
            $stmt = $conn->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '`' . ";\n\n";
            $sql .= 'USE `' . DB_NAME . "`;\n\n";

            if ($this->disableForeignKeyChecks === true) {
                $sql .= "SET foreign_key_checks = 0;\n\n";
            }

            foreach ($tables as $table) {
                /**
                 * CREATE TABLE
                 */
                $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;';
                $query = 'SHOW CREATE TABLE `' . $table . '`';
                $stmt = $conn->query($query);
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $sql .= "\n\n" . $row[1] . ";\n\n";

                /**
                 * INSERT INTO
                 */

                $query = 'SELECT COUNT(*) FROM `' . $table . '`';
                $stmt = $conn->query($query);
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $numRows = $row[0];

                $numBatches = intval($numRows / $this->batchSize) + 1;
                for ($b = 1; $b <= $numBatches; $b++) {
                    $query = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * $this->batchSize - $this->batchSize) . ',' . $this->batchSize;
                    $stmt = $conn->query($query);
                    $realBatchSize = $stmt->rowCount();
                    $numFields = $stmt->columnCount();

                    if ($realBatchSize !== 0) {
                        $sql .= 'INSERT INTO `' . $table . '` VALUES ';
                        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                            $sql .= '(';
                            for ($j = 0; $j < $numFields; $j++) {
                                if (isset($row[$j])) {
                                    $row[$j] = addslashes($row[$j]);
                                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                                    $row[$j] = str_replace("\r", "\\r", $row[$j]);
                                    $row[$j] = str_replace("\f", "\\f", $row[$j]);
                                    $row[$j] = str_replace("\t", "\\t", $row[$j]);
                                    $row[$j] = str_replace("\v", "\\v", $row[$j]);
                                    $row[$j] = str_replace("\a", "\\a", $row[$j]);
                                    $row[$j] = str_replace("\b", "\\b", $row[$j]);
                                    if ($row[$j] == 'true' or $row[$j] == 'false' or preg_match('/^-?[1-9][0-9]*$/', $row[$j]) or $row[$j] == 'NULL' or $row[$j] == 'null') {
                                        $sql .= $row[$j];
                                    } else {
                                        $sql .= '"' . $row[$j] . '"';
                                    }
                                } else {
                                    $sql .= 'NULL';
                                }

                                if ($j < ($numFields - 1)) {
                                    $sql .= ',';
                                }
                            }
                            $sql .= "),\n"; //close the row
                        }

                        $this->saveFile($sql);
                        $sql = '';
                    }
                }
                $sql .= "\n\n";
            }

            if ($this->disableForeignKeyChecks === true) {
                $sql .= "SET foreign_key_checks = 1;\n";
            }

            $this->saveFile($sql);

            echo json_encode(array('status' => true, 'msg' => 'Backup exitoso guardado en ' . $this->backupDir . '/' . $this->backupFile));
        } catch (\Exception $th) {
            echo  json_encode(array('status' => false, 'msg' => $th->getMessage() . 'xd'));
        }
    }
    protected function saveFile(&$sql)
    {
        if (!$sql) return false;

        try {

            if (!file_exists($this->backupDir)) {
                mkdir($this->backupDir, 0777, true);
            }

            file_put_contents($this->backupDir . '/' . $this->backupFile, $sql, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        return true;
    }
}
