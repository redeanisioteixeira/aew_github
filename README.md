# AMBIENTE EDUCACIONAL WEB - AEW

O __Ambiente Educacional Web__ – __AEW__ é um projeto de aprendizagem (baseado em __ZEND FRAMEWORK 1.11__) voltado ao compartilhamento de conteúdos digitais através de licenças livres, disponibilizado na internet no endereço (__http://ambiente.educacao.ba.gov.br__) para acesso e utilização de todos; constituído pelos seguintes módulos: 

* __Conteúdos Digitais__: conteúdos educacionais registrados principalmente como  __licenças livres__
* __Sites Temáticos__: referencias a web sites desenvolvidos por instituições parceiras que disponibilizam mídias educacionais livres das mais variadas áreas do conhecimento e temas transversais
* __Ambientes de Apoio e a Produção__: são referencias a softwares livres ou aplicações pedagógicos específicos para a produção de mídias educacionais.
* __Espaço Aberto__: uma rede social educacional em que professores e estudantes podem publicar seus próprios textos, compartilhar mídias, criar e participar de comunidades de ensino-aprendizagem

O principal objetivo do __AEW__ é ser um ambiente pedagógico e multidisciplinar para que estudantes e professores possam acessar, compartilhar e construir conhecimentos por meio das novas tecnologias da informação e comunicação, sempre respeitando as __licenças livres__.  

## SOFTWARE LIVRE

Estamos liberando o código da aplicação para cumprir com o propósito para o qual foi criado o __Ambiente Educacional Web__, ser um __software livre__, para que qualquer pessoa ou também outras secretarias de educação no Brasil sejam parte deste projeto aberto para a comunidade de desenvolvimento, por isso o convidamos a instalar o aplicativo em seu servidor local. 

## COMO ESTA IMPLEMENTADO 

Básicamente o projeto esta implementado na arquitetura __MVC__ (Modelo - Vista - Controlador) para isso foi estabelecido utilizar: 

* Backend -  Zend Framework 1.11 
* (PHP 5.3)
* Servidor Apache 2.2
* Banco de Dados - Postgres 9.1 ou superior
* Frontend - JQuery 1.11 / Bootstrap 3.6 / plugins de JQuery / Outros


## COMO INSTALAR

A equipe de desenvolvimento utiliza o sistema operacional __Ubuntu__ como ambiente de desenvolvimento e seguiremos este mini tutorial segundo as configurações que criamos em Ubuntu. Se você tem outra distribuição __Linux__ ou sistema operacional siga as instruções de algum __HOW TO__ para configurar um novo virtual host em sua maquina.

### CRIE UM REPOSITORIO GIT

Faça o download do repositorio desde a página [Rede Anísio Teixeira](http://redeanisioteixeira.github.io/aew_github/ "Página Oficial")
ou faça o clone do repositorio se já esta familiarizado com a ferramenta GIT

` $ git clone https://github.com/redeanisioteixeira/aew_github.git `

### POSTGRESQL

Desde o terminal entre como usuario admin
 
` $ su - postgres `<br>
` postgres> psql `

Crie banco de dados e asigne a um usuário

` Postgres> createdb -U usuarioDoBanco nomeDoBancoNovo `

Liste todos os bancos de datos para comprobar si criou o banco

` Postgres> \l `

Se conecte ao banco de datos

` postgres> \c nomeDoBancoNovo `

E faz o restore do banco [db_ambiente_educaional_web.sql](https://github.com/redeanisioteixeira/aew_github/blob/master/db_ambiente_educaional_web.sql "dump do banco")

` postgres> \i nomeDoArquivo.backup ou .sql ` 

### USUÁRIOS

| Usuários       | Login        | Senha  |
| ------------- |:-------------:| -----:|
| Super administrador | admin | admin@aew |
| Administrador      | administrador | administrador@aew |
| Coordenador | coordenador | coordenador@aew |
| Editor | editor | editor@aew |
| Colaborador | colaborador | colaborador@aew |
| Amigo da Escola| amigodaescola| amigodaescola@aew |


### VIRTUAL HOST E CONFIGURAÇÃO DO APACHE ###

Com o editor nano ou gedit crie o arquivo __ambiente.educacional.web__ na pasta /etc/apache2/sites-available 

` $ sudo nano /etc/apache2/sites-available/ambiente.educacional.web ` 

Cole o seguinte codigo

```

<VirtualHost *:80>
DocumentRoot "/var/www/ambiente.educacional.web/aew_sec/public"
ServerName ambiente.educacional.web

# Diretiva que aponta na pasta de conteúdos digitais, onde ficarão físicamente guardados
AliasMatch ^/conteudos/(.*) "/media/srv/conteudos/$1"

# Diretiva para colocar o sistema em desenvolvimento para DEBUG da aplicação
SetEnv APPLICATION_ENV "development"
# Diretiva de opções de diretorio
<Directory "/var/www/ambiente.educacional.web/aew_sec/public">
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    allow from all
    Options +Indexes
</Directory>
</VirtualHost>

```

Guarde o arquivo e crie um link simbólico do arquivo na pasta /etc/apache2/sites-enables

` $ cd /etc/apache2/sites-available/ ` <br>

` $ sudo ln -s /etc/apache2/sites-available/ambiente.educacional.web /etc/apache2/sites-enabled/ `


Confirme si criou o link simbólico na pasta /etc/apache2/sites-enables

` $ sudo ls -l `


Adicione seu virtual host (ambiente.educacional.web) na configuração do arquivo /etc/hosts

` $ sudo nano /etc/hosts `

Reinicíe seu servidor apache

` $ sudo service apache2 restart `

Entre no seu navegador e digite http://ambiente.educacional.web assim já estará instalada a aplicação, lembre que é necesario habilitar o módulo rewrite para reescrever as urls.


### APPLICATION.INI

Entre na pasta ` aew_sec/application/configs/application.ini ` e edite as siguentes linhas  

```
[production]
constants.MEDIA_PATH = /dados/srv1  // guarda arquivos físicos para manté-los separados da aplicação
constants.CONTEUDO_PATH = conteudos  

;;; Modules
resources.db.params.dbname = "xxxxxxx"   // Nome do Banco de Dados 
resources.db.params.host =  "xxx.xxx.xxx.xxx" // localhost ou IP
resources.db.params.username = "xxxxxxx" // Usuário utilizador do Banco
resources.db.params.password = "xxxxxxx" // Senha do Banco

```

## CONTRIBUTORS

* Coordenador Geral Yuri Wanderley
* Coordenador Equipe de desenvolvimento Lisandro Monje
* Desenvolvedor Tiago Lima 
* Desenvolvedor Nicolás Romero
* Designer Josymar Álves


## CRÉDITOS

* Secretaria da Educação do Estado da Bahia
* Rede Anísio Teixeira - Instituto Anísio Teixeira
