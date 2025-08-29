# AlpesOne Challenge

API REST para gerenciamento de carros com integração da API AlpesOne.

## Requisitos para Rodar

- Docker com o Docker Compose instalado

## Sobre a Estrutura Docker

O Docker está configurado com três estágios diferentes, definidos pela target no build ou no docker-compose:

1. **Base**: Ambiente base com PHP 8.4 e extensões essenciais.
2. **Development**: Ambiente de desenvolvimento com todas as dependências do Composer e entrypoint específico.
3. **Testing**: Semelhante ao Development, mas sem executar o entrypoint (não executa migrations).
4. **Production**: Versão otimizada para deploy com entrypoint de produção que inclui o cron.

Os serviços no docker-compose são:

1. **app**: Aplicação Laravel com PHP-FPM
2. **db**: Banco de dados MySQL 9
3. **nginx**: Servidor web

## Inicialização do Ambiente de Desenvolvimento

1. Clone este repositório: `git clone <LINK> && cd alpesone-challenge`

2. Suba os containers: `docker-compose up`

3. Acesse a aplicação em: `http://localhost`

O script de entrypoint `docker-entrypoint.sh` gera o arquivo .env e executa as migrations automaticamente.
Configure o .env com os dados do banco conforme necessário.

Após a configuração, reinicie o container da aplicação: `docker-compose restart app`

## API Endpoints

A API possui os seguintes endpoints:

### Autenticação
- **POST /api/register** - Registro de usuário
- **POST /api/login** - Login de usuário
- **GET /api/me** - Dados do usuário atual (requer autenticação)
- **POST /api/logout** - Logout (requer autenticação)

### Gerenciamento de Carros
- **GET /api/cars** - Listar todos os carros
- **GET /api/cars/{id}** - Obter detalhes de um carro
- **POST /api/cars** - Cadastrar novo carro (requer autenticação)
- **PUT /api/cars/{id}** - Atualizar um carro (requer autenticação)
- **DELETE /api/cars/{id}** - Excluir um carro (requer autenticação)

Tem uma coleção do postman anexado a raiz do projeto para testar os endpoints: [Postman Collection](Cars.postman_collection.json)

## Integração com API AlpesOne

A aplicação inclui um comando Artisan para sincronização de dados da API AlpesOne:

```bash
php artisan app:alpes-one-sync
```

Este comando busca dados de carros da API externa e atualiza o banco de dados local. Pode ser executado manualmente ou via cron conforme configurado no ambiente de produção.

## CI/CD Workflow

O arquivo `.github/workflows/main-ci.yml` configura o workflow de CI/CD automatizado que é executado sempre que há alterações na branch principal:

### Integração Contínua (CI)
1. **Trigger**: Ativado automaticamente em cada push para a branch `main`
2. **Ambiente**: Executado em uma máquina virtual Ubuntu mais recente no GitHub Actions
3. **Preparação**: Configura o ambiente copiando o arquivo `.env.example` para `.env`
4. **Build**: Constrói a imagem Docker com o target "testing" (`docker build --target testing -t alpesone-app .`)
5. **Testes**: Executa a suíte de testes automatizados via `composer test` em um container isolado
6. **Notificação**: Gera alerta em caso de falha nos testes

### Entrega Contínua (CD)
1. **Dependência**: Só é executado se a etapa de testes for bem-sucedida
2. **Conexão**: Estabelece conexão SSH com a instância EC2 usando credenciais armazenadas como segredos
3. **Atualização**: Faz pull da branch main no repositório no servidor
4. **Deployment**: Reconstrói e reinicia os containers usando `docker-compose -f docker-compose.prod.yml up -d --build`
5. **Limpeza**: Remove imagens antigas não utilizadas para liberar espaço no servidor

## Configuração da VM para Deploy

- A criação da VM EC2 está documentada no arquivo [create-ec2.sh](create-ec2.sh)
- A configuração pós-criação da VM está documentada em [script-ec2.sh](script-ec2.sh)
