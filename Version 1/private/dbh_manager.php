<?php

function get_database() {
    static $pdo = null;

    if ($pdo === null) {
        $pdo = connect_to_database();
    }

    return $pdo;
}

function connect_to_database() {
    $host = 'localhost';
    $dbname = "INSERT DB NAME";
    $dbusername = 'INSERT DB USERNAME';
    $dbpassword = 'INSERT DB PASSWORD';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function run_query(string $query, array $params = []) {
    try {
        $stmt = get_database()->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'server has gone away') !== false) {
            connect_to_database();
            run_query($query, $params);
        } else {
            error_log("DATABASE ERROR:" . $e->getMessage());
        }
    }
}
