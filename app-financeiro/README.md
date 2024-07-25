# LOJAHUB FINANÇAS

## Projeto Laravel Etapas
- abrir wsl

- clonar projeto dentro do wsl
```sh
git clone git@bitbucket.org:academiadoecommerce/app-financeiro.git
```
- ver se o docker está rodando 
```sh
docker ps
```

- versão do php 
```sh
php -v
```

- versão do composer 
```sh
composer --version
```
- observação: caso não tenha o alias do sail use sempre `./vendor/bin/sail`

- build do laravel sail
```sh
sail build
```

- subir laravel sail
```sh
sail up -d
```

- acessar projeto [http://localhost](http://localhost)
