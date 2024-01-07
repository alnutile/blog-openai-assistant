# OpenAi Assistant Blog Post

Read [here](coming soon)

## Install

Setup like a normal Laravel app

We are using a diffrent version of the openai/laravel library:

```json 
    "openai-php/laravel": "dev-add-assistants-api",
``` 
and then set that at the bottom of the `composer.json`

```json    
    "minimum-stability": "dev",
    "prefer-stable": true
```

## Seed

```bash
php artsian migrate --seed
```
