# attendance-app　　
Dockerビルド  
1.git@github.com:suzu1279/attendance-app.git  
2.cd coachtech-laravel-attendance-app  
3.DockerDesktopアプリを立ち上げる  
4.docker-compose up -d --build  

Laravel環境構築  
1.docker-compose exec php bash  
2.composer install  
3.「.env.example」ファイルを「.env」ファイルに命名変更。または、新しく.envファイルを作成。  
4..envに以下の環境変数を追加  
DB_CONNECTION=mysql  
DB_HOST=mysql  
DB_PORT=3306  
DB_DATABASE=laravel_db  
DB_USERNAME=laravel_user  
DB_PASSWORD=laravel_pass  
5.アプリケーションキーの作成　php artisan key:generate  
6.マイグレーションの実行　php artisan migrate  
7.シーディングの実行　php artisan db:seed  
8.シンボリックリンク作成　php artisan strage:link  

使用技術（実行環境）  
・PHP8.1-fpm  
・Laravel8.83.27  
・MySQL8.0.26  



