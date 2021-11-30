1. Instructions on how to run your solution

The project uses docker with docker-compose. To start it you have tu run:
$ docker-compose up -d

Then install the vendors:
$ docker-compose exec php-fpm composer install

To run the command that solves the 3 examples specified in the test:
$ docker-compose exec php-fpm bin/console app:vending:run

-> It will show by console the result of each examples and the final status of products of the vending machine.

To run the tests suite:
$ docker-compose exec php-fpm bin/phpunit

2. COMMENTS:

I tried to solve it some days but I had some problems with the initial project, and then I decide to create a
new clean project and then put the solution code using git flow and feature branches. I never implement an in memory 
solution instead of using a database and doctrine, and maybe it costs me a little bit more than other commonly for me 
solution.

About the test suite, I only create some tests but not all, because they are a small sample about them. As I comment
before I used to work with doctrine and I mock the repositories when I do unit tests. Of course all classes must have
they complete tests to assure the behaviour is right.

I hope it will be interesting for you this solution.