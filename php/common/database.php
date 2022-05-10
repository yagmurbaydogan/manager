<?php

class DataFrame
{
    public $header;
    public $rows;

    public function __construct($header, $rows)
    {
        $this->header = $header;
        $this->rows = $rows;
    }
}

class Database
{
    const USERNAME = 'USERNAME';
    const PASSWORD = 'PASSWORD';
    const CONNECTION_STRING = "CONNECTION_STRING";

    /**
     * @param string $path path to .sql file to execute
     * @param bool $verbose display the errors on webpage
     * @param bool $stopOnError stop when one query within file fails to execute
     * @return array|false array containing the dataframe for each query executed, false on failure
     * End of each separate query should be marked with ';'
     */
    static function executeFile($path, $verbose=true, $stopOnError=true) {
        // parses sql file and execute all statements within
        $queries = file_get_contents($path);

        if (!$queries && $verbose) {
            echo "<br>File cannot be opened: " . $path . "<br>";
            return false;
        }

        $results = [];
        $queries = explode(';', $queries);

        // execute each query stored within file
        $db_conn = self::getConnection();
        if (!$db_conn) {
            return false;
        }

        foreach ($queries as $query) {
            // skip empty queries
            if (trim($query) == '') {
                continue;
            }

            // make sure each query succeeds before continuing needed
            $queryRes = self::executeSQL($db_conn, $verbose, $query);
            if (!$queryRes && $stopOnError) {
                return false;
            }

            // store all results for future
            array_push($results, self::retrieveDataframe($queryRes));
        }

        OCILogoff($db_conn);
        return $results;
    }

    /**
     * @param string $cmd_str query to execute
     * @param bool $verbose display the errors on webpage
     * @return DataFrame|false dataframe containing query results, false on error
     */
    static function executePlainSQL($cmd_str, $verbose=true) {
        // takes a plain (no bound variables) SQL command and executes it
        $db_conn = self::getConnection();
        if (!$db_conn) {
            return false;
        }

        $result = self::retrieveDataframe(self::executeSQL($db_conn, $verbose, $cmd_str));
        OCILogoff($db_conn);
        return $result;
    }

    /**
     * @param string $cmd_str query to execute
     * @param array $bindings array oi
     * @param bool $verbose display the errors on webpage
     * @return DataFrame|false dataframe containing query results, false on error
     */
    static function executeBoundSQL($cmd_str, $bindings, $verbose=true) {
        /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
        In this case you don't need to create the statement several times. Bound variables cause a statement to only be
        parsed once then you can reuse the statement. This is also very useful in protecting against SQL injection.
        See the sample code below for how this function is used */
        $db_conn = self::getConnection();
        if (!$db_conn) {
            return false;
        }

        $result = self::retrieveDataframe(self::executeSQL($db_conn, $verbose, $cmd_str, $bindings));
        OCILogoff($db_conn);
        return $result;
    }

    static private function getConnection() {
        $db_conn = OCILogon(self::USERNAME, self::PASSWORD, self::CONNECTION_STRING);
        if (!$db_conn) {
            // For OCILogon errors pass no handle
            $e = OCI_Error();
            echo htmlentities($e['message']);
            return false;
        }
        return $db_conn;
    }

    static private function retrieveDataframe($result) {
        $header = array();
        $num_cols = OCI_NUM_FIELDS($result);
        for ($i = 1; $i <= $num_cols; ++$i) {
            array_push($header, OCI_FIELD_NAME($result, $i));
        }

        $rows = array();
        while ($row = OCI_FETCH_ARRAY($result, OCI_NUM)) {
            array_push($rows, $row);
        }
        return new DataFrame($header, $rows);
    }

    static private function executeSQL($db_conn, $verbose, $cmd_str, $bindings=false) {
        // There are a set of comments at the end of the file that describe some OCI specific functions and how they work
        $statement = OCIParse($db_conn, $cmd_str);

        // statement parsing
        if (!$statement && $verbose) {
            echo "<br>Cannot parse the following command: " . $cmd_str . "<br>";
            // For OCIParse errors pass the connection handle
            $e = OCI_Error($db_conn);
            echo htmlentities($e['message']);
            return false;
        }

        // binding stage
        if ($bindings) {
            foreach ($bindings as $key => $val) {
                $r = OCI_BIND_BY_NAME($statement, $key, $val);
                if (!$r && $verbose) {
                    echo "<br>Cannot bind: " . $key . " to " . $val . "<br>";
                    // For OCIExecute errors pass the statement handle
                    $e = oci_error($statement);
                    echo htmlentities($e['message']);
                }
                // Make sure you do not remove this
                // Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
                unset ($val);
            }
        }

        // execution
        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r && $verbose) {
            echo "<br>Cannot execute the following command: " . $cmd_str . "<br>";
            // For OCIExecute errors pass the statement handle
            $e = oci_error($statement);
            echo htmlentities($e['message']);
            return false;
        }

        OCICommit($db_conn);
        return $statement;
    }
}
