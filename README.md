# attendance-app　　
Dockerビルド

git@github.com:suzu1279/mock-project.git
cd coachtech-laravel-mock-project
DockerDesktopアプリを立ち上げる
docker-compose up -d --build
Laravel環境構築　　

docker-compose exec php bash
composer install
「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
.envに以下の環境変数を追加
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
アプリケーションキーの作成 　php artisan key:generate
マイグレーションの実行　　php artisan migrate
シーディングの実行　　php artisan db:seed
シンボリックリンク作成　　php artisan storage:link
使用技術（実行環境）
・PHP8.1-fpm
・Laravel8.83.27
・MySQL8.0.26
