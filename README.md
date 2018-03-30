# Sistema de análise de transações #

### Sobre o sistema ###

__S__ istema de análise de transações financeiras.

## Instalação ##

* Pacotes
````bash
$ composer install
````
 Após instalar os pacotes através do gerenciador de dependências (Composer), crie um banco e insira suas configurações de conexões no arquivo __phinx.yml__ na raiz do projeto, como mostra o exemplo abaixo.
 ````yml
 environments:
    default_migration_table: migrationlog
    default_database: production
    production:
        adapter: mysql
        host: localhost
        name: bd_name
        user: user_db
        pass: 'pass_db'
        port: 3306
        charset: utf8
 ````

Em seu terminal, vá para a raiz do projeto e execute o comando abaixo para criar as tabelas no banco de dados.
````bash
$ vendor\bin\phinx migrate -e production
````
Após isso, execute o comando descrito abaixo para popular as tabelas:
````bash
$ vendor\bin\phinx seed:run
````
> Para melhor entendimento do processo de modalagem do banco, verifique a url http://ondras.zarovi.cz/sql/demo/?keyword=bernhoeft .

No arquivo _config.php_ a constante __BASE_URL__ define a url padrão do servidor. Já a constante __PASS_USER__ define qual a senha padrão dos novos usuários cadastrados no sistema.
````php

<?php

define("BASE_URL", "http://localhost");

define("PASS_USER", "teste");

````

### Atenção!

O _.htaccess_ deve ser alterado juntamente com a constante __BASE_URL__ caso a url padrão do servidor aponte para alguma pasta.

___Mizael Melo___ - 
*PHP Developer*