## Install Docker container 

```
$ composer install
$ cd docker
$ docker-compose up -d
$ cd ..
$ ./run_migration.sh
```
## Check Database
```
docker-compose exec database mysql -u root --password=password
```
## Deployment CSS und JSServing Assets in dev vs prod

For the prod environment, before deploy, you should run:
```
$ php bin/console asset-map:compile
```
This will physically copy all the files from your mapped directories to public/assets/ so that they're served directly by your web server. See Deployment for more details.

If you run the ```asset-map:compile``` command on your development machine, you won't see any changes made to your assets when reloading the page. To resolve this, delete the contents of the ```public/assets/``` directory. This will allow your Symfony application to serve those assets dynamically again.
php bin/console asset-map:compile


