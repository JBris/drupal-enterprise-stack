# drupal-enterprise-stack

* [Drupal](#drupal)  
* [Nginx](#nginx)  
* [PostgreSQL](#postgresql)  
* [Redis](#redis)  

## Drupal <a name="drupal"/>

*Link*: https://www.drupal.org/8

Drupal 8 comes preconfigured and heavily leverages the other services available in the docker stack. The latest release of composer will be installed during the image build.

All included contrib modules can be found here: https://github.com/JBris/drupal-enterprise-stack/tree/master/app/modules/contrib

## Nginx <a name="nginx"/>

Nginx acts as the Drupal service's web server. The default configuration file can be found here: https://github.com/JBris/drupal-enterprise-stack/blob/master/services/nginx/conf.d/default.conf

To connect to the drupal service, `server drupal:9000;` must be included in an upstream block.

## PostgreSQL <a name="postgresql"/>

The Drupal stack uses Postgres as its database. 

Database dumps can be imported and exported using `make dbimp` and `make dbexp` respectively. Dumps can be found in the `data` directory.

The following settings can be configured in your .env file:

| Name        | Default Value           |  
| ------------- |:-------------:|  
| DB_NAME     | drupal | 
| DB_USER    | user      |    
| DB_PASSWORD | pass     |    
| DB_ROOT_PASSWORD | password     |    
| DB_HOST | postgres     |    
| DB_PORT | 5432     |    
| DB_CONTAINER_PORT | 5432     |    
| DB_DRIVER | pgsql     |    


## Redis <a name="redis"/>

*Link:* https://www.drupal.org/project/redis

The Drupal stack uses the PHPRedis as its PHP client. This extension will be installed during the Drupal image build process.

Enable the Redis module. Visit localhost/admin/reports/redis to view the current status of the Redis module.

Ensure that the following settings have been added to the settings.php file.

```
$settings['redis.connection']['interface'] = 'PhpRedis'; 
$settings['redis.connection']['host'] = 'redis';  
$settings['redis.connection']['port'] = '6379';  
$settings['cache']['default'] = 'cache.backend.redis';
$settings['queue_default'] = 'queue.redis_reliable';
$settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';
```
