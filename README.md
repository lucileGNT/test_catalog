This tool helps you to insert Album and song data in your database from a given xml file.

To install the project :

- Clone the project in your local environment
- Install Composer by following the steps here : https://getcomposer.org/download/
- In your terminal, run : <pre>composer install</pre> to install all the needed libraries
- Create an empty database. Edit the database and server details in 'config/bootstrap.php' config file :
$dbParams = array(
'host'     => '127.0.0.1',
'driver'   => 'pdo_mysql',
'user'     => 'root',
'password' => '',
'dbname'   => 'test_catalog',
);
- In your terminal, run : <pre>vendor/bin/doctrine orm:schema-tool:update --dump-sql --force</pre> to create the tables according to the project entities.
- Run <pre>php init.php</pre> script to fill the XmlMapping table

The project is now ready! To start parsing the xml file, run <pre>php console.php</pre>
