Example of usage neo4j with php connector
-------------------------------

How to run this project:

1. Install composer
curl -s http://getcomposer.org/installer | php
2. Be sure php ext-curl extension is installed
3. Install required packages php composer.phar install
3.a If you want to run unitests you need php composer.phar update --dev
4. Download and unpack neo4j graph database
5. Run neo4j (bin/neo4j start)
6. Add folowing parameters to parameter.yml
>     neo4j_host: 127.0.0.1 <- depends on your neo4j configuration
>     neo4j_port: 7474      <- depends on your neo4j configuration
7. Run Symfony console php app/console -s  and execute neo4j:load-data
8. Open project in browser and enjoy



You can test it also online fg.sztart.pl


Tips:
Neo4j is easy accessible on heroku.com