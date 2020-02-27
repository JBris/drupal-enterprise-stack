# drupal-enterprise-stack

* [Drupal](#drupal)  
* [Drush](#drush)  
* [Nginx](#nginx)
* [OAuth2](#oauth2)  
* [PostgreSQL](#postgresql)  
* [Redis](#redis)  
* [Elasticsearch and Kibana](#elasticsearch-kibana)  
* [Mailhog](#mailhog)  

## Drupal <a name="drupal"/>

*Link*: https://www.drupal.org/8

Drupal 8 comes preconfigured and heavily leverages the other services available in the docker stack. 

The latest release of composer will be installed during the image build. Composer can be called using `make composer a="$ARG"`. For example, `make composer a="require drupal/redis"` to retrieve the Redis module and its dependencies.

All included contrib modules can be found here: https://github.com/JBris/drupal-enterprise-stack/tree/master/app/modules/contrib

An example settings.php file can be found here. https://github.com/JBris/drupal-enterprise-stack/blob/master/app/sites/default/settings.php. Docker environment variables can be accessed using the `getenv()` function.

## Drush <a name="drush"/>

*Link*: https://www.drush.org/

A separate drush container has been included to execute drush commands on your Drupal instance. These can be executed using `make drush a="$ARG"`. For example, `make drush a="cr"` will clear the Drupal cache.

## Nginx <a name="nginx"/>

Nginx acts as the Drupal service's web server. The default configuration file can be found here: https://github.com/JBris/drupal-enterprise-stack/blob/master/services/nginx/conf.d/default.conf

To connect to the drupal service, `server drupal:9000;` must be included in an upstream block.

## OAuth2 <a name="oauth2"/>

*Link*: https://www.drupal.org/project/simple_oauth

*Link*: https://github.com/thephpleague/oauth2-client

First, enable the Simple OAuth module. 

Next, visit http://localhost/admin/config/people/simple_oauth. You may either use an existing public-private key pair or generate a new pair by selecting `Generate keys`. Then select the `Add Client` button. Fill out the inputs fields as required. The **New Secret** field is an optional secret key needed to create a new token. Assign a user role to the **Scopes** checkbox lists (you may need to create a new role first). Finally, click `Save`.

Test that your configuration is successful by making a **POST** request to http://localhost/oauth/token. The request body should be submitted as multipart form data. 

| Key           | Value         |  
| ------------- |:-------------:|  
| grant_type    | password      | 
| client_id     | $UUID         |    
| username      | username      |    
| password      | password      |    
| client_secret | secret        |    

The *client_id* will be one of the UUIDs listed at http://localhost/admin/config/services/consumer. The client_secret is an optional value that you would have entered when creating the OAuth client via the **New Secret** field.

## PostgreSQL <a name="postgresql"/>

The Drupal stack uses Postgres as its database. 

Database dumps can be imported and exported using `make dbimp` and `make dbexp` respectively. Dumps can be found in the `data` directory.

The following settings can be configured in your .env file:

| Name          | Default Value |  
| ------------- |:-------------:|  
| DB_NAME       | drupal        | 
| DB_USER       | user          |    
| DB_PASSWORD   | pass          |    
| DB_ROOT_PASSWORD | password   |    
| DB_HOST       | postgres      |    
| DB_PORT       | 5432          |    
| DB_CONTAINER_PORT | 5432      |    
| DB_DRIVER     | pgsql         |    


## Redis <a name="redis"/>

*Link:* https://www.drupal.org/project/redis

Redis has been included for performant and scalable caching and job queueing purposes.

The Drupal stack uses the PHPRedis as its PHP client. This extension will be installed during the Drupal image build process.

Enable the Redis module. Visit http://localhost/admin/reports/redis to view the current status of the Redis module.

Ensure that the following settings have been added to the settings.php file.

```
$settings['redis.connection']['interface'] = 'PhpRedis'; 
$settings['redis.connection']['host'] = 'redis';  
$settings['redis.connection']['port'] = '6379';  
$settings['cache']['default'] = 'cache.backend.redis';
$settings['queue_default'] = 'queue.redis_reliable';
$settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';
```

## Elasticsearch and Kibana <a name="elasticsearch-kibana"/>

*Link:* https://www.drupal.org/project/search_api

*Link:* https://www.drupal.org/project/elasticsearch_connector

Elasticsearch and Kibana have been included in the Docker stack to facilitate powerful full-text search functionality.

Firstly, enable the Search API and Elasticsearch Connector modules.

Visit http://localhost/admin/config/search/elasticsearch-connector. Select `Add cluster`. By default, the server URL will be http://elasticsearch:9200 - the URL to access the Elasticsearch container within a Docker bridge network.

Next, visit http://localhost/admin/config/search/search-api. Click `Add server`. Select Elasticsearch as your backend, and choose your newly configured Elasticsearch cluster from the drop-down list.

Finally, click `Add index`. Tick the content that you want Elasticsearch to index from the checkbox list. Choose your newly configured Elasticsearch server from the bulletpoint list. You can either save your configuration now or select the fields that you wish to index next. Finally, you may select `Search API processors` to modify your search queries (e.g. string tokenizers and field weightings).

## Mailhog <a name="mailhog"/>

*Link:* https://github.com/mailhog/MailHog

*Link:* https://github.com/mailhog/mhsendmail

The Mailhog service has been included in the Docker stack to capture emails during local development.

The mhsendmail package will be installed during the Docker build phase. This package acts as a sendmail replacement.

The PHP sendmail path will be set to `sendmail_path='/usr/local/bin/mhsendmail --smtp-addr="mailhog:1025` in `/usr/local/etc/php/conf.d/sendmail.ini` within the Drupal container.
