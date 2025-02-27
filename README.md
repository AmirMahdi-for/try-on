# 🎭 Try-On Laravel Package

Welcome to the **Try-On Laravel Package**! 🚀 This package allows you to process virtual try-on requests with ease using external services.

## ⚠️ Prerequisite

This package relies on the **Fal.AI Try-On API** ([Fal.AI Try-On](https://fal.ai/models/fashn/tryon/api)) for processing virtual try-on requests. To use this package, you must have access to the Fal.AI API. Please ensure you have registered for an API key and have the necessary credentials before proceeding with the installation.

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

Ensure that the `TRY_ON_SERVICE_API` variable points to the **Fal.AI Try-On API**.

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
✅ Classifies clothing into categories (tops, bottoms, one-pieces)  
✅ Sends images for virtual try-on processing  
✅ Retrieves and tracks the try-on process status  
✅ Stores results in the database asynchronously  

## 🏗 Running Migrations

```bash
php artisan migrate
```

## 🏗 Queue Processing
To handle speech recognition requests asynchronously:

```bash
php artisan queue:work
```

## 📜 License

This package is open-source and licensed under the **MIT License**. 🎉

## 🤝 Contributing

Contributions are welcome! Feel free to submit issues or pull requests. Let's make virtual try-ons better together! 💡

## 📞 Support
For support, please open an issue on GitHub or contact me personally via email at **amirmahdifor@gmail.com**.

