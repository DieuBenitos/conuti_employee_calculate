cd docker
docker-compose up -d
cd ..
./run_migration.sh

docker-compose exec database mysql -u root --password=password

If you run the asset-map:compile command on your development machine, you won't see any changes made to your assets when reloading the page. To resolve this, delete the contents of the public/assets/ directory. This will allow your Symfony application to serve those assets dynamically again.
php bin/console asset-map:compile


