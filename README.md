# PHP RUNNING CONTROL API

Para rodar o projeto via docker, é necessário que tenhamos o docker e docker-compose instalados em nossa máquina.

Detalhes sobre a instalação do docker podem ser encontradas [aqui](https://marcosteodoro.dev/blog/dockerizando-seu-ambiente-de-desenvolvimento-php/).

O ambiente conta com um container de PHP (versão 8) rodando com Nginx.

Na aplicação foi utilizado o Symfony e mais detalhes sobre sua documentação podem ser encontrados [aqui](https://symfony.com/doc/current/index.html)

## Rodando o projeto
Para rodar o projeto, faça o clone do mesmo em um diretório de sua preferência e rode os comandos listados abaixo:

Iniciar containeres do projeto:
```
docker-compose up -d
```

Instalação das dependências do projeto:
```
docker-compose exec app composer install
```
Criação da estrututa do banco de dados de testes:
```
docker-compose exec app php bin/console doctrine:migrations:migrate --env=test -n
```

Adição das fixtures do banco de dados de testes:
```
docker-compose exec app php bin/console doctrine:fixtures:load --env=test -n
```

Criação da estrutura do banco de dados de produção:
```
docker-compose exec app php bin/console doctrine:migrations:migrate -n
```

Após isso o projeto está pronto para utilização.

## Mais detalhes
O projeto foi desenvolvido utilizando testes, baseado na metodologia do TDD.

Para executar a suíte de testes basta rodar o seguinte comando:
```
docker-compose exec app php bin/phpunit
```

Contamos com duas bases de dados para o projeto, a principal da aplicação em si, e uma base replicada para execução dos testes, todas funcionando no docker.

## Utilidades
Salve este arquivo [insomnia-php-running-control-api-collection.json](https://github.com/marcosteodoro/php_running_control_api/blob/main/insomnia-php-running-control-api-collection.json) e importe no Insomnia para ter acesso as rotas implementadas.
