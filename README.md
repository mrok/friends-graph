Example of usage neo4j with php connector
-------------------------------

How to run this project:

1. Install composer
curl -s http://getcomposer.org/installer | php
2. Be sure php ext-curl extension is installed
3. Install required packages php composer.phar install
4. Download and unpack neo4j graph database
5. Run neo4j (bin/neo4j start)
6. Add folowing parameters to parameter.yml
>     neo4j_host: 127.0.0.1
>     neo4j_port: 7474
7. Run Symfony console and execute neo4j:load-data
8. TODO
- vizualize graph
- remove duplicated relation
- store user in db