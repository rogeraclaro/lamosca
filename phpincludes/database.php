<?php
/**
 * Database Abstraction Layer for PHP 8.x Migration
 * Replaces deprecated mysql_* functions with mysqli
 */

// Database configuration - reads from environment or uses defaults
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'mylamosca';
$db_password = getenv('DB_PASS') ?: '4XvEvhm1';
$dbname = getenv('DB_NAME') ?: 'weblamosca';

// Path configuration based on environment
if (getenv('DB_HOST') === 'db') {
    // Docker environment
    $imgroot = "/img/";
    $imgrootsrv = "/var/www/html/img/";
    $myServer = "/";
} else {
    // Production/test subdirectory
    $imgroot = "/test/img/";
    $imgrootsrv = "/usr/home/lamosca.com/web/test/img/";
    $myServer = "/test/";
}

// Table names
$categorytable = "categories";
$projecttable = "projects";
$moduletable = "modules";
$mosaictable = "mosaic";

// Global connection variable
$db_connection = null;

/**
 * Establish database connection
 * @return mysqli|false
 */
function db_connect() {
    global $db_host, $db_user, $db_password, $dbname, $db_connection;

    if ($db_connection !== null) {
        return $db_connection;
    }

    $db_connection = mysqli_connect($db_host, $db_user, $db_password, $dbname);

    if (!$db_connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Set charset - use latin1 for legacy data compatibility
    mysqli_set_charset($db_connection, "latin1");

    return $db_connection;
}

/**
 * Execute a query (replacement for mysql_db_query)
 * @param string $dbname Database name (ignored, kept for compatibility)
 * @param string $sql SQL query
 * @return mysqli_result|bool
 */
function db_query($dbname, $sql) {
    $conn = db_connect();
    $result = mysqli_query($conn, $sql);

    if (!$result && mysqli_errno($conn)) {
        error_log("MySQL Error: " . mysqli_error($conn) . " - Query: " . $sql);
    }

    return $result;
}

/**
 * Fetch row as indexed array (replacement for mysql_fetch_row)
 * @param mysqli_result $result
 * @return array|null
 */
function db_fetch_row($result) {
    if (!$result) return null;
    return mysqli_fetch_row($result);
}

/**
 * Fetch row as associative array (replacement for mysql_fetch_array)
 * @param mysqli_result $result
 * @param int $result_type
 * @return array|null
 */
function db_fetch_array($result, $result_type = MYSQLI_BOTH) {
    if (!$result) return null;
    return mysqli_fetch_array($result, $result_type);
}

/**
 * Fetch row as associative array only
 * @param mysqli_result $result
 * @return array|null
 */
function db_fetch_assoc($result) {
    if (!$result) return null;
    return mysqli_fetch_assoc($result);
}

/**
 * Get number of rows (replacement for mysql_num_rows)
 * @param mysqli_result $result
 * @return int
 */
function db_num_rows($result) {
    if (!$result) return 0;
    return mysqli_num_rows($result);
}

/**
 * Get number of affected rows (replacement for mysql_affected_rows)
 * @return int
 */
function db_affected_rows() {
    global $db_connection;
    if (!$db_connection) return 0;
    return mysqli_affected_rows($db_connection);
}

/**
 * Get last insert ID (replacement for mysql_insert_id)
 * @return int
 */
function db_insert_id() {
    global $db_connection;
    if (!$db_connection) return 0;
    return mysqli_insert_id($db_connection);
}

/**
 * Escape string for SQL (replacement for mysql_real_escape_string)
 * @param string $string
 * @return string
 */
function db_escape($string) {
    $conn = db_connect();
    return mysqli_real_escape_string($conn, $string);
}

/**
 * Get last error message (replacement for mysql_error)
 * @return string
 */
function db_error() {
    global $db_connection;
    if (!$db_connection) return mysqli_connect_error();
    return mysqli_error($db_connection);
}

/**
 * Close database connection (replacement for mysql_close)
 */
function db_close() {
    global $db_connection;
    if ($db_connection) {
        mysqli_close($db_connection);
        $db_connection = null;
    }
}

// Backwards compatibility aliases - maps old mysql_* patterns to new functions
// These allow gradual migration without changing all code at once

/**
 * @deprecated Use db_query() instead
 */
function mysql_db_query($dbname, $sql) {
    return db_query($dbname, $sql);
}

/**
 * @deprecated Use db_fetch_row() instead
 */
function mysql_fetch_row($result) {
    return db_fetch_row($result);
}

/**
 * @deprecated Use db_fetch_array() instead
 */
function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
    return db_fetch_array($result, $result_type);
}

/**
 * @deprecated Use db_num_rows() instead
 */
function mysql_num_rows($result) {
    return db_num_rows($result);
}

/**
 * @deprecated Use db_affected_rows() instead
 */
function mysql_affected_rows() {
    return db_affected_rows();
}

/**
 * @deprecated Use db_insert_id() instead
 */
function mysql_insert_id() {
    return db_insert_id();
}

/**
 * @deprecated Use db_error() instead
 */
function mysql_error() {
    return db_error();
}

/**
 * @deprecated Use db_close() instead
 */
function mysql_close($link = null) {
    db_close();
}

/**
 * @deprecated Handled by db_connect() automatically
 */
function mysql_connect($server, $username, $password) {
    // Connection is handled by db_connect()
    // This is kept for compatibility with existing code
    return db_connect();
}

/**
 * @deprecated Use db_query() instead
 * Wrapper for mysql_query (without database name parameter)
 */
function mysql_query($sql) {
    global $dbname;
    return db_query($dbname, $sql);
}
