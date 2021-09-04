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

