Projeto desenvolvido como um desafio proposto durante um processo seletivo na área de TI.
Objetivo principal: Criar uma API para um aplicativo de agendamento de espaços para camping.


<b>Get started</b>

Clonar repositório para o local desejado

```shell
git clone https://github.com/throcha3/desafio-agendamento.git
```

Baixar dependencias
```shell
composer update
```

Criar/atualizar chaves do passport
```shell
composer passport:install --force
```

Criar manualmente banco de dados no mysql, e editar o .env de acordo com a configuração do servidor.

Rodar migrations
```shell
php artisan migrate
```
Alimentar banco de dados com dados iniciais
```shell
php artisan db:seed
```
Pronto! O sistema está preparado para uso.

Na pasta raiz do projeto haverá um arquivo JSON contendo a collection para ser importada no Postman, com exemplos de todos os endpoints do sistema.

O formato para datas será sempre 'y-m-d', tanto no get quando no post. Ex: '20-12-31'

O parametro Authorization é requerido em todos os endpoints. O valor do parametro será o token_id retornado no endpoint de login.
Endpoints do tipo POST tem um corpo no formato JSON, e do tipo GET, os parametros serão passados por parametros query.

