Haltes
========

Introduction
-----------

This application is created to give a overview of all the locations of all the public transport stops in The Netherlands.
With this application it's possible to see all stops on a map and calculate distances between stops.
It makes use of the Zend Framework 2 together with Doctrine.
The stops are searchable with the form on the left side of the page.

The web application also has an API side. This is included to create connection possibility with other application.
By making use of this REST API, an array of stops can be returned in JSON format.

Setup
----------

Create a database and use the credentials which can be filled in the config/autoload/global.php file.

After the database has been created the application can be build.
This can be done by running the following command in the root directory.

~~~
ant
~~~

When running this command, the following tasks will be done:
- downloading new files with stops (source: http://gtfs.ovapi.nl/)
- Empty the current database
- Creating tables in database and filling those with busstops and tracks
- Building the application

Using the application
----------------------

When opening the webapplication it's possible to see the map and some form.
The form is used to look for specific stops which will be shown in the info sector below and on the map.

The RESTAPI can be accessed by using the following urls:
- /busstops
- /busstops/[Stopcode]
- /busstops?town=Amsterdam
- /Path/[Stopcode]/[Stopcode]

