<?php


// Custom Theme Settings
add_action('admin_menu', 'add_existing_interface');
function add_existing_interface()
{
    add_management_page('Import from existing database', 'Import from existing database', 'manage_options', 'import_existing_page', 'existing_import');
}

function existing_import()
{
    // Just import ten for now
    function should_import_document()
    {
        static $count = 0;
        $count++;
        return $count <= 10;
    }

    echo "<h1>Existing Import</h1>";


    // Connect to the database
    $mysqli = new mysqli('localhost', 'infospaceimport', 'infospaceimport', 'infospaceimport');

    if ($mysqli->connect_errno) {
        echo "<p>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "</p>";
    } else {
        //require __DIR__ . '/initial-import-files/import-documents.php';
        //require __DIR__ . '/initial-import-files/import-links.php';
        //require __DIR__ . '/initial-import-files/import-pages.php';
        //require __DIR__ . '/initial-import-files/import-news.php';
        //require __DIR__ . '/initial-import-files/import-newsletters.php';
        //require __DIR__ . '/initial-import-files/import-users.php';
        require __DIR__ . '/initial-import-files/import-logs.php';
    }

    //Show existing data tables

    $result = $mysqli->query("SHOW TABLES");
    if ($result) {
        echo "<ul>";
        while ($row = $result->fetch_array()) {
            $table = $row[0];
            echo "<li><strong>$table</strong><ul>";
            $fields = $mysqli->query("SHOW COLUMNS FROM `$table`");
            if ($fields) {
                while ($field = $fields->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($field['Field']) . "</li>";
                }
                $fields->free();
            }
            echo "</ul></li>";
        }
        echo "</ul>";
        $result->free();
    } else {
        echo "<p>Error fetching tables: " . $mysqli->error . "</p>";
    }
    $mysqli->close();
}
