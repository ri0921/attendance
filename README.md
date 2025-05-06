# coachtech 勤怠管理アプリ

## 概要

coachtech勤怠管理アプリは、ある企業が開発した独自の勤怠管理ツールです。ユーザーが出勤・退勤を簡単に記録し、管理者が勤怠を効率的に確認・承認できる仕組みを提供します。  
ユーザーの日々の勤怠を記録・管理し、煩雑な勤怠確認・申請・承認業務の効率化を図ることを目的としています。

- **目標**：初年度でユーザー数 1,000人の達成
- **ターゲット**
  - 企業に勤める一般社員
  - 勤怠を管理する管理者・マネージャー
- **使用技術**：Laravel / Fortify / MySQL / Docker

---

## 環境構築

### Dockerビルド
* リポジトリをクローン
```
git clone git@github.com:ri0921/attendance.git
```
* Dockerをビルド
```
docker-compose up -d --build
```

### Laravel環境構築
* PHPコンテナに入る
```
docker-compose exec php bash
```
* Laravelパッケージのインストール
```
composer install
```
* .envファイル作成、環境変数を変更
```
cp .env.example .env
```
* アプリケーションキーの生成
```
php artisan key:generate
```

## 使用技術
* PHP 7.4.9
* Laravel 8.83.8
* MySQL 8.0.26
* MailHog 1.0.1

## ER図
![ER図](.png)

## URL
* 開発環境：<http://localhost/>
* phpMyadmin：<http://localhost:8080/>
* MailHog：<http://localhost:8025/>