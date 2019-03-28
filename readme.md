# Facebook-Post
Repositório criado para contrução de uma aplicação teste para realizar e agendar postagens de imagens e mensagens para páginas do Facebook.

**É necessário criar um aplicativo no Facebook developers**

[Para criar aplicativos do facebook acesse este link](https://developers.facebook.com)

Após acessar o link, selecione o menu meus aplicativos e adicionar novo aplicativo.

**Para executar a aplicação local com o aplicativo, execute os seguintes passos:** 

- acesse a página principal do aplicativo no Facebook Developers, selecione configuração, básico;
- em domínios, insira localhost;
- desca a barra de rolagem (na mesma configuração), em site, URL do site e insira: http://localhost:8000/;

**Algumas aplicações do Facebook exigem um certificado ssl (https), caso não funcione, poderá utilizar o ngrok para realizar um tunelamento do seu localhost com certificado ssl e inserir a url gerada no lugar de localhost como descrito anteriormente.**

[Link do ngrok](https://ngrok.com/)

**Para executar a aplicação siga os passos abaixo:**

- clonar o repositório para uma pasta local (https: git clone https://github.com/felipeqf/facebook_post.git | ssh: git@github.com:felipeqf/facebook_post.git) ou baixe o repositório;
- acesse o repositório e digite composer install;
- abrir o diretório do projeto em um editor de texto, duplicar o arquivo .env.example renomeando para .env e insira as configurações abaixo:

- FACEBOOK_APP_ID={id do aplicativo do facebook}
- FACEBOOK_APP_SECRET={chave secreta do aplicativo do facebook}

- digite php artisan key:generate;
- escolha um banco de dados e altere os dados de conexão do arquivo env conforme abaixo:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nome_do_banco
DB_USERNAME=nome_do_usuario
DB_PASSWORD=senha_do_usuario

- execute o comando php artisan migrate.
- execute php artisan serve e acesse a url do projeto por um web browser com o endereço localhost:8000;
- ao clicar em login será solicitado usuário e senha do Facebook ou continuar como caso esteja logado, após inormar o usuário, deverá escolher as páginas a gerenciar e utilizar o sistema.

**Caso não aparece o aplicativo do Facebook criado pelas etapas anteriores, revise se faltou alguma informação ou se há algo errado com o aplicativo.**







