Rowan Libraries Medical Ebooks Search
=====================================

The Medical E-Books Search application allows front end users to search and browse e-books via Keywords, Phrases, and Boolean searches and to filter results along subject facets.  The application utilizes
Apache Solr to index and search data and provides a secure password protected  administrative section to manage E-Book data and users  

# Requirements

* PHP 5.6+
* MySQL 5+ or MariaDB 10
* Apache SOLR 6.2+

# Installation

# Set Up Solr Core

Copy the 'sorl/ebooks' core configuration to your solr instance and restart solr

# Application Set

* Create Your Database on your MySQL server
* Checkout the application to a folder you wish for your server document root e.g. <code>/var/www/ebooks</code>
* Create a new apache VM for this application with the document root as set from the previous step
* Set the environment for this application e.g. export SYMFONY_ENV=prod or export SYMFONY_ENV=dev or export SYMFONY_ENV=test (this shoudl get set at boot

# Configuration

Set up the site parameters in app/config; The parameters.yml file is used to store site configuration such as database connection information, solr host config, and more

Copy parameters.yml.dist to the following depending on he SYMFONY_ENV

IF SYMFONY_ENV=prod, THEN set parameters.yml

IF SYMFONY_ENV=dev, THEN set parameters_dev.yml

IF SYMFONY_ENV=test, THEN set paramters_test.yml


# Database Setup

Once the database has config information has been set in the parameters.yml (or the file for you environment) the database must be initialized.

Test your Database connection:

<pre>
<code>
php app/console doctrine:schema:validate

[Mapping]  OK - The mapping files are correct.
[Database] FAIL - The database schema is not in sync with the current mapping file.
</code>
</pre>

The <code>[Mapping] OK</code> message means the database connection information in the parameters.yml file are correct and the application can connect to the database

The <code>[Database] FAIL</code> message means the schema hasn't been initialized

Next, create the schema (you only need to rune scheme create once.  If you change the scheme you use database migrations

<pre>
<code>
php app/console doctrine:schema:create

ATTENTION: This operation should not be executed in a production environment.

Creating database schema...
Database schema created successfully!
</code>
</pre>


# Bulk Import Data

# Bulk Update Search Index