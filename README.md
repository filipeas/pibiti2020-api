* API para o aplicativo de captura de lesões – LIMCI

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
