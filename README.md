* API para o aplicativo de captura de lesões – LIMCI

* Configurações

## Configurações iniciais

O camicase foi construído sobre o framework Laravel. É impresindível manter o código sempre atualizado e funcional, no entanto, os servidores compartilhados que estão sendo usados no momento para rodar esse sistema não são atualizados, necessitando de configurações para funcionar. Assim, é necessário:

- Instalar php 7.3 no servidor.
- Instalar composer.
- Instalar git.

Após essas configurações básicas, na pasta App/Providers, abra a classe AppServiceProvider, dentro do método boot adicione o seguinte:
``` \Illuminate\Support\Facades\Schema::defaultStringLength(191); ```
Essa configuração só é necessária caso o mysql esteja em versões menores que 5.7.

No servidor da Hostgator é necessário inserir o seguinte comando no .htaccess da pasta public do projeto:
``` <IfModule mime_module> AddHandler application/x-httpd-ea-php73 .php .php7 .phtml </IfModule>  ```
Esse comando irá setar o php 7.3 á execução do projeto.

## Comandos após a finalização das configurações básicas
``` composer install ``` <br>
``` npm run dev ``` <br>
``` php artisan key:generate ``` <br>
``` php artisan migrate ``` <br>
``` php artisan passport:install ``` <br>
``` php artisan storage:link ``` <br>

OBS: Ao rodar composer install o laravel talvez pessa algumas extensões, como php-xml. Caso isso ocorra, realizar a seguinte sequência de instalação no terminal:

``` sudo apt-get update ``` <br>
``` sudo apt install php-xml ``` <br>
``` sudo apt-get install php-mbstring ``` <br>

Logo após isso, basta rodar composer update ou composer install.

* PS1: Caso queira executar o projeto na rede local para abrir o App pelo celular e deixar a API rodando no computador, execute o comando ``` php artisan serve --host=ip_da_maquina ```. Esse comando irá por padrão usar a porta 8000. Lembre-se de liberar acesso as portas pelo firewall do computador. Caso queira, se estiver usando linux e apache2, crie uma regra de liberação para a porta 8000 usando o comando ``` sudo ufw allow 8000 ```.

* PS2: Quando for executar a função de segmentação da lesão, verificar o caminho do script dentro da API (deve ser feito a atualização das variáveis que guardam o caminho).

* Descrição

Essa API possui todas as rotas necessárias para que o aplicativo exerça suas principais funções, listadas abaixo:

- Permitir acesso de especialistas e pacientes
- Permitir manipulação de Análises clínicas realizadas pelo especialista
- Permitir manipulação de informações de pacientes
- Permitir armazenamento e processamento de lesões, capturadas através de fotos

* Rotas

- As rotas estão divididas em: **públicas** e **privadas nível de usuário**.
- Todas as rotas privadas por nível de usuário devem ter os seguintes Headers:
  - **Accept**: application/json
  - **Authorization**: Bearer {token do usuário}

- **Públicas**:
  - Login
    - http://pibiti2020.camicase.com.br/api/login
    - Verbo: POST
    - Parâmetros: email, password
  - Registrar Especialista
    - http://pibiti2020.camicase.com.br/api/access
    - Verbo: POST
    - Parâmetros: name, lastname, cpf, professional_record, email, password, c_password

- **Privadas para usuário do tipo especialista**:
  - Cadastrar paciente
    - http://pibiti2020.camicase.com.br/api/patient/access
    - Verbo: POST
    - Parâmetros: name (obrigatório), lastname (obrigatório), cpf (obrigatório), email, password (obrigatório), c_password (obrigatório)

  - Cadastrar análise
    - http://pibiti2020.camicase.com.br/api/analyse
    - Verbo: POST
    - Parâmetros: user (obrigatório), description (obrigatório)

  - Listar todas as análises
    - http://pibiti2020.camicase.com.br/api/analyse
    - Verbo: GET
    - Parâmetros: não tem

  - Listar todas as análise do especialista
    - **Não foi feito**.
    - Verbo: GET
    - Parâmetros: 

  - Listar uma análise do especialista
    - http://pibiti2020.camicase.com.br/api/analyse/{id_da_analise}
    - Verbo: GET
    - Parâmetros: id_da_analise

  - Editar análise
    - http://pibiti2020.camicase.com.br/api/analyse/1?description=oi
    - Verbo: PUT
    - Parâmetros: description (obrigatório)

  - Remover análise
    - http://pibiti2020.camicase.com.br/api/analyse/1
    - Verbo: DLETE
    - Parâmetros: não precisa

  - Cadastrar lesão
    - http://pibiti2020.camicase.com.br/api/lesion
    - Verbo: POST
    - Parâmetros: analyse (obrigatório), original_image (obrigatório), checked_image (obrigatório), description (obrigatório)

  - Listar todas as lesões
    - http://pibiti2020.camicase.com.br/api/lesion
    - Verbo: GET
    - Parâmetros: não tem

  - Listar as lesões de um paciente
    - **Não foi feito**.
    - Verbo: GET
    - Parâmetros: 

  - Remover uma lesão de um paciente
    - http://pibiti2020.camicase.com.br/api/lesion/{id_da_lesao}
    - Verbo: DELETE
    - Parâmetros: não tem
