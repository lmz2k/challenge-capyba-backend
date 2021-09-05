# Desafio backend Capyba - Gabriel Lima

Para contemplar os requisitos desse desafio, foi desenvolvido uma API para um sistema de divulgação de vagas de emprego
para DEVS, onde é possível criar e procurar vagas.

## 1 - Framework

Para esse desafio foi escolhido o framework PHP [Lumen](https://lumen.laravel.com/docs), um micro-framework do [Laravel](https://laravel.com/docs/contributions).

O laravel é um framework super completo, porém o lumen é uma versão mais enxuta, trazendo apenas as principais features
necessárias para o desenvolvimento de uma API, visando isso, o escolhi para solucionar esse desafio.

## 2 - Features

1 - Principais:

- Endpoint para cadastro de usuário :white_check_mark:
- Endpoint de termos de uso e políticas de privacidade :white_check_mark:
- Endpoint Login :white_check_mark:
- Endpoint Logout :white_check_mark:
- Endpoint com listagem de itens públicos ( com todos requisitos ) :white_check_mark:
- Endpoint para listagem de itens restritos ( com todos requisitos ) :white_check_mark:
- Endpoint para editar perfil :white_check_mark:
- Endpoint para confirmar token recebido por email :white_check_mark:
- Endpoint para reenviar token para email :white_check_mark:
- Testes :white_check_mark:
- SWAGGER :white_check_mark:
- README :white_check_mark:


2 - Bônus

- Deploy :white_check_mark:
- Acesso admin 	:x:
- Endpoint para alterar senha com confirmação da senha atual :white_check_mark:
- Cadastro via API do google 	:x:
- Seed :white_check_mark:

## 3 - Regra de negócio

Ao criar uma conta no sistema, será enviado um email para o email inserido, com um código, esse código deve ser confirmado.
( Mesmo com a conta criada mas o email ainda não tenha sido confirmado, o sistema deve bloquear acesso do usuário nas
rotas restritas ), caso o email não chegue, ou tenha o desejo de gerar um novo código, o sistema também disponibiliza 
essa funcionalidade.

É possível procurar de forma paginada vagas disponíveis, podendo filtrar por modo de contratação **( PJ, CLT ou AMBOS )**,
área de atuação **( BACKEND, FRONTEND ou FULLSTACK)**, também é possível filtrar se deseja apenas vagas **home office** ou não,
é possível **pesquisar via texto** também pelo **nome, descrição ou cidade da vaga**, e decidir a **ordenação** por
**data de anúncio** da vaga e/ou **salário**.


Na área restrita do usuário logado, é possível criar uma nova vaga, e listar apenas suas vagas criadas com os mesmos
filtros e ordenações da listagem anterior.



Ainda na área restrita o usuário consegue alterar a senha de sua conta, precisando confirmar sua senha atual,
e também editar dados da sua conta como, nome, email e foto.

## 4 - Estrutura do projeto

| Diretório | Responsabilidade |
| --------  | ---------------- |
| **/database** |  *Migrations e Seeders do projeto* |
| **/routes**   |  *Declaração dos endpoints* |
| **/tests**    |  *Testes do sistema* |
| **/app/Exception** | *Exceções customizadas* |
| **/app/Http/Controllers** | *Controllers da aplicação* |
| **/app/Http/Middleware**  | *Middlewares da aplicação* |
| **/app/Models** | *Modelos dos objetos da aplicação* |
| **/app/Repositories** | *Repositórios que comunicam diretamente com database*
| **/app/Services** | *Serviços responsáveis por gerir a regra de negócio da aplicação* |

## 5 - Setup

### 1 - Instalar o PHP 7.4 e o composer

Como não tenho conhecimento em qual SO irá ser execultado, deixarei o link para documentação oficial.

[Instalar PHP](https://www.php.net/manual/pt_BR/install.php)

[Instalar COMPOSER](https://getcomposer.org/doc/00-intro.md)

### 2 - Baixar o projeto para maquina
Pode ser feito o download do ZIP pelo proprio github, ou pelo terminal via linha de
comando, e entrar na pasta do projeto

```sh 
git clone https://github.com/lmz2k/challenge-capyba-backend.git

challenge-capyba-backend
```

###3 - Instalar dependencias

```shell
composer install
```

###4 - Configurar variáveis de ambiente

Existe um arquivo na raiz do projeto chamado .env.example, basta renomeá-lo para apenas .env ou criar uma cópia dele.

Algumas variáveis estão sem valores, esses valores foram enviados em um arquivos TXT junto com email de entrega do desafio,
é necessário substituir essas variáveis, pelas que estão nesse arquivo recebido via email, para que funcione tudo como deveria
(upload de imagem, envio de email e conexão com banco de dados).

As variáveis relacionadas ao banco de dados, são as únicas que podem ser alteradas caso desejem
utilizar um banco de dados próprio, porém o banco que enviei para vocês, já é um banco meu que está pronto para funcionar.

###5 - Migrations e Seeders

Primeiramente rodar migrations


```shell
php artisan migrate
```

Existem 4 Seeders no projeto, que são:

- Seed de estados (Obrigatória), para popular a tabela de estados do sistema.
- Seed de cidades (Obrigatória), para popular a tabela de cidades do sistema.

Para roda-las,  use os seguintes comandos

```shell
php artisan db:seed --class=StateSeeder

php artisan db:seed --class=CitySeeder
```

Existem outras duas seeds no projeto:

- Seed de Usuários (Opcional), para popular a usuários com algumas contas por default.
- Seed de Vagas (Opcional), para popular a tabela de vagas com vagas aleatórias ( importante que essa seed pode ser
  executada N vezes, porém antes de executá-la, a seed de usuários tem que ter sido executada pelo menos 1 vez)


```shell
php artisan db:seed --class=UserSeeder

php artisan db:seed --class=VacanciesSeeder
```

## 6 - Rodar o projeto

Nessa etapa iremos rodar o projeto, e rodar os tests do sistema.

para inicializar o projeto digite:

```shell
 php -S localhost:8000 public/index.php   
```

com o projeto rodando, podemos visualizar documentação do **swagger** no seguinte link:

```shell
http://localhost:8000/api/documentation
```

agora podemos rodar os testes para validar que o setup foi bem sucedido,  execulte o comando:

```shell
./vendor/bin/phpunit 
```

pronto agora o projeto está 100% configurado e basta usar e abusar dos EP para testar as funcionalidade xD

## 7 - Links

[Coleção no postaman](https://www.getpostman.com/collections/6baa49b7d461c37a0df4)

[Deploy](https://www.getpostman.com/collections/6baa49b7d461c37a0df4)

[SWAGGER on deploy](https://www.getpostman.com/collections/6baa49b7d461c37a0df4)
