Run this in terminal to create migration, model, controller, and routes in web.php. You can change table name to any other like blogs to services or anything
and also you can change field name and add or remove field name dynamically.
# php artisan blogcrud:make-migration blogs --fields="title:string,content:text,author:string,featured_image:string"
