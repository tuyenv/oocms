git fetch
git add .
git commit -m "Comment"
git push


** DATABASE
Create new database
php app/console doctrine:database:create
Update structure
php app/console doctrine:schema:update --force


**** Symfony 2 ****

/app
	/config
		- parameters.yml
			.front_theme_id = core/front
			.admin_theme_id = core/admin
/src
	/CoreBundle
		/Controller
			- UserController.php
				/user/login
				/user/register
				/user/forgot-password
			- AdminController.php
				/admin
				/admin/login
				/admin/forgot-password
			- AdminUserController.php
				/admin/user
				/admin/user/{user_id}/edit
				/admin/user/{user_id}/change-password
			- AdminSettingController.php
				/admin/setting
	/DemoBundle
		/Controller/
			- SimpleController.php.simple
				/hello-world
				/item
				/item/{id}
				/form
			- AdminSimpleController.php.simple
				/admin/simple
				/admin/simple/item
				/admin/simple/item/{id}/edit
				/admin/simple/form
		/Entity
			Item.php.simple
			FormItem.php.simple
	/Extend (***gitignore -- Working here)
		/MusicBunble
			/Controller
				- ArticleController.php
					/article
					/article/{id}
				- ContactController.php
					/contact
			/Entity
				- Article.php
				- Contact.php
		

/web
	/themes
		/core
			/front
				/views
			/admin
				/views
			/system
				/views
		/extend (***gitignore -- working here)
			/phanrich
				/views
			