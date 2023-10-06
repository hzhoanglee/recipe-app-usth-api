## This is the API for Recipe App

This api is for the Recipe App. It is built with Laravel 10. App Repo: https://github.com/hzhoanglee/recipe-app-usth

### To run the app locally:
1. Clone the repo
2. Run `composer install`
3. Create a database and add the credentials to the .env file
4. Run `php artisan migrate`
5. Run `php artisan app:parse-data`
6. Run `php artisan serve`

### Web Routes
- Firebase: /firebase
- Requests: /foodrequest

### API Endpoints
- GET|HEAD  | api/auth/error                              login
- GET|HEAD  | api/auth/firebase   |       Api\AuthController@registerFirebaseToken
- POST      | api/auth/login               |       Api\AuthController@login
- POST      | api/auth/logout             |       Api\AuthController@logout
- GET|HEAD  | api/auth/me                   |       Api\AuthController@me
- GET|HEAD  | api/auth/payload             |       Api\AuthController@payload
- GET|HEAD  | api/auth/refresh             |       Api\AuthController@refresh
- POST      | api/auth/register           |       Api\AuthController@register
- GET|HEAD  | api/categories             |       Api\CategoryController@index
- GET|HEAD  | api/categories-data     |       Api\CategoryController@categoryData
- POST      | api/favorite/delete |       Api\FavouriteController@deleteFavourite
- GET|HEAD  | api/favorite/list     |       Api\FavouriteController@listFavourite
- POST      | api/favorite/save     |       Api\FavouriteController@newFavourite
- GET|HEAD  | api/recipe/detail         |       Api\RecipeController@recipeData
- GET|HEAD  | api/recipe/explore         |       Api\RecipeController@explore
- GET|HEAD  | api/recipe/featured     |       Api\RecipeController@featuredRecipe
- POST      | api/recipe/request     |       Api\RecipeController@recipeRequest
- GET|HEAD  | api/recipe/search       |       Api\RecipeController@searchRecipe
- GET|HEAD  | api/regFirebase       |       Api\AuthController@newFirebaseToken
