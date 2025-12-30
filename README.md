# Ontology-Reasoner-DentistRecommender

How to Run : 


frontend : 
- Using laravel website
- Run Commands
  ```
  composer install
  php artisan key:generate
  php artisan serve
  ```
  Make sure to include the Database setup on the .env file (For session key generation).

if  cannot may to install composer first / composer install.

backend : 
- use library owlready2 for reasoning swrl rule with pellet and flask api
- should have a installed java for running owready2. 
- pip install flask owlready2
- run on port 5000 / python app.main

optional : 
- check a owlready2 running with a command python test_ontology.php
 

