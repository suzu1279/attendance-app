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

テーブル設計  
<img width="798" height="229" alt="スクリーンショット 2025-12-16 17 03 33" src="https://github.com/user-attachments/assets/8452910f-7aa6-47c6-910e-b675d5963a43" />  
<img width="793" height="253" alt="スクリーンショット 2025-12-16 17 04 10" src="https://github.com/user-attachments/assets/301f99f4-6e77-47df-96c5-8cf942e22c73" />  
<img width="792" height="229" alt="スクリーンショット 2025-12-16 17 04 25" src="https://github.com/user-attachments/assets/dc0cc514-8cfd-4df7-a1e3-48b5853124af" />  
<img width="795" height="307" alt="スクリーンショット 2025-12-16 17 04 42" src="https://github.com/user-attachments/assets/072d7ea4-3b89-482b-a8ca-789ec6193073" />  

ER図  
<img width="386" height="434" alt="スクリーンショット 2025-12-16 17 08 59" src="https://github.com/user-attachments/assets/256a413b-8d49-4f95-9ac1-96e66d46c1db" />









