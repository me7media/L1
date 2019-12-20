# L1
geekHub 2019 homework lesson 1

# Use
cd public

php -S localhost:8000

## Creating a product

http://localhost:8000/create?name=Name&category_id=1&price=1&qt=1

product with params will be created

## Moving a product to another category

http://localhost:8000/move?id=2&category_id=3

will move product category

http://localhost:8000/delete?id=1

will delete product
