# 🎯 Try-On Laravel Package

Welcome to the **Try-On Laravel Package**! 🚀 This package allows you to process virtual try-on requests with ease using external services.

## 📦 Installation

You can install the package via Composer:

```sh
composer require caraxes/try-on
```

Then, publish the configuration file:

```sh
php artisan vendor:publish --tag=config --provider="TryOn\Providers\TryOnServiceProvider"
```

## ⚙️ Configuration

Add the required environment variables to your `.env` file:

```ini
CATEGORY_IDENTIFIER_API=<your_api_url>
CATEGORY_IDENTIFIER_TOKEN=<your_token>
TRY_ON_SERVICE_TOKEN=<your_token>
TRY_ON_SERVICE_API=<your_api_url>
```

## 🚀 Usage

### Getting Clothing Category

Use the `TryOnRepository` to classify a clothing item:

```php
$repository = app(\TryOn\Repositories\TryOnRepository::class);
$category = $repository->getCategory('T-shirt');
echo $category; // Outputs: tops, bottoms, or one-pieces
```

### Virtual Try-On

To process a try-on request:

```php
$parameters = [
    'message' => [
        'productTitle' => 'Jeans',
        'image' => 'https://example.com/model.jpg',
        'productImage' => 'https://example.com/jeans.jpg'
    ]
];

$result = $repository->tryOn(1, $parameters);
print_r($result);
```

## 🛠️ Features
👉 Classifies clothing into categories (tops, bottoms, one-pieces)  
👉 Sends images for virtual try-on processing  
👉 Retrieves and tracks the try-on process status  
👉 Stores results in the database asynchronously  

## 🏗 Running Migrations

```bash
php artisan migrate
```

## 🏗 Queue Processing
To handle try-on requests asynchronously:

```bash
php artisan queue:work
```

## 🔬 Running Tests

You can run the package tests using:

```sh
php artisan test
```

## ⚡ Continuous Integration (CI/CD)

This package uses **GitHub Actions** to ensure code quality and compatibility with **Laravel 11**.  
The workflow (`.github/workflows/ci.yml`) includes the following steps:

1. **Checkout Repository** 🏢  
   Pulls the latest code from the repository.  

2. **Setup PHP Environment** 🐘  
   Installs PHP 8.2 along with required extensions and tools.  

3. **Cache Dependencies** 🛠️  
   Caches Composer dependencies for faster builds.  

4. **Install Laravel 11** 🏠  
   Installs a fresh Laravel 11 project to test the package.  

5. **Install Try-On Package** 📚  
   Configures Composer to load the `try-on` package from the local repository.  

6. **Optimize Laravel** ⚡  
   Runs `config:cache` and `optimize` for better performance.  

7. **Run Migrations** 💜  
   Applies database migrations to prepare the environment for testing.  

8. **Run Static Analysis** 🔍  
   Runs PHPStan to check for potential issues in the codebase.  

The workflow is triggered on **push** and **pull request** events for `main` and `develop` branches.  

## 🐟 License

This package is open-source and licensed under the **MIT License**. 🎉

## 🤝 Contributing

Contributions are welcome! Feel free to submit issues or pull requests. Let's make virtual try-ons better together! 💡

## 📞 Support
For support, please open an issue on GitHub or contact me personally via email at **amirmahdifor@gmail.com**.

