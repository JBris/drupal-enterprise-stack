# drupal-enterprise-stack

* [PostgreSQL](#postgresql)  
* [Redis](#redis)  


## PostgreSQL <a name="postgresql"/>

The Drupal stack uses Postgres as its database. 

Database dumps can be imported and exported using `make dbimp` and `make dbexp` respectively.

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

The Drupal stack uses the PHPRedis as its PHP client. This extension should be installed during the Drupal image build process.

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
