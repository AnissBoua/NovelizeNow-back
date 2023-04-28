# NovelizeNow

This template should help get you started developing with symfony 6.

## Recommended Setup

PHP 8

## Project Setup

```sh
composer install
```

### Create JSON keys

```sh
php bin/console lexik:jwt:generate-keypair
```


### Ecouter les Webhook Stripe 

Installer le Stripe CLI: https://stripe.com/docs/stripe-cli

```sh
stripe listen --forward-to http://127.0.0.1:8000/api/stripe/checkout-completed-webhook
```

Enfin mettre le Stripe Webhook Key dans le .env