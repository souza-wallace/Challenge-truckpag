
# Challenge TruckPag
[This is a challenge by Coodesh](https://coodesh.com/)

### Passo a passo
Clone Repositório
```sh
git clone 
git@github.com:souza-wallace/truckpag.git
https://github.com/souza-wallace/truckpag.git
```
```sh
cd truckpag
```

Suba os containers do projeto
```sh
docker-compose up -d
```

Acesse o container app
```sh
docker-compose exec app bash
```

Crie o Arquivo .env
```sh
cp .env.example .env
```

Instale as dependências do projeto
```sh
composer install
```


Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Importe o sistema de autenticação.
```sh
composer require laravel/sanctum
```

Publique o serviço.
```sh
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Rode as migrações.
```sh
php artisan migrate
```
Rode as o seed, ele ira criar um usuário.
```sh
php artisan db:seed
```
Importe o swagger para trabalhar com a documentação da API.
```sh
composer require "darkaonline/l5-swagger"
```
Registre o serviço.
```sh
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```
Rode o comando.
```sh
php artisan l5-swagger:generate
```
Verifique se L5_SWAGGER_GENERATE_ALWAYS  foi adicionado ao seu .env, caso não adicione-o como true.
```dosini
L5_SWAGGER_GENERATE_ALWAYS=true
```

Importe o scout e Publique o serviço. Se precisar de alguma configuração extra pode ver em: <a href="https://laravel.com/docs/10.x/scout#installation" target="_blank">Laravel Scout</a>
```sh
composer require laravel/scout
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

Importe o driver do algolia.
```sh
composer require algolia/algoliasearch-client-php
```
Adicione essa linha ao seu .env
```dosini
SCOUT_QUEUE=true
```

Para Configurar seu scout acesse o:  <a href="https://laravel.com/docs/10.x/scout#installation" target="_blank">Algolia</a> e pegue os valores de Application ID e Admin API Key e adicione no seu .env
```dosini
ALGOLIA_APP_ID=**************
ALGOLIA_SECRET=******************************
```

Importe o horizon, ele fornece um belo painel e configuração baseada em código para suas filas Redis alimentadas pelo Laravel . O Horizon permite monitorar facilmente as principais métricas do seu sistema de filas, como taxa de transferência de trabalho, tempo de execução e falhas de trabalho.
```sh
composer require laravel/horizon
```

Execute o comando de importação dos dados
```sh
php artisan cron:importDataApi
```

Importe os dados para seu serviço de indexação
```sh
php artisan scout:import "App\Models\Product"
```

Execute o horizon para enviar os dados ao mongolia
```sh
php artisan scout:import "App\Models\Product"
```

Acesse o projeto
[http://localhost:8989](http://localhost:8989)
