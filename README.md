# AlpesOne Challenge

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

### Requisitos para rodar

- Docker com o Docker Compose instalado

Temos três estágios no Dockerfile - que são definidos pelas targets no `docker-compose.yml`:

1. **Base**: É o começo de tudo. Tem o PHP 8.4 com as extensões essenciais.
2. **Development**: Ambiente pra codar. Instala todas as dependências do composer.
3. **Testing**: Pra rodar os testes. Não roda o entrypoint script - não queria que rodasse as migrations no ci.
4. **Production**: Versão optimizada pra deploy. Não instala dependências de dev.

O `docker-compose.yml` tem três serviços:

1. **app**: A aplicação Laravel em si com o php fpm
2. **db**: Banco de dados MySQL 9
3. **nginx**: Servidor web

### Como rodar tudo

Tô te mostrando o passo a passo, meu consagrado:

1. Clone esse repositório: `git clone .... && cd alpesone-challenge`

2. Suba os containers: `docker-compose up`

3. Estará disponível em: `http://localhost`

O script `docker-entrypoint.sh` já vai fazer o .env e subir as migrations. Depois só configure o .env com os dados do
banco e qualquer coisa só reiniciar o container do laravel: `docker-compose restart app`

### Como funciona o workflow

O arquivo `.github/workflows/main-ci.yml` faz o seguinte:

1. É ativado quando alguém faz push na branch main
2. Roda em um ambiente Ubuntu mais recente
3. Configura o ambiente copiando o .env.example
4. Constrói a imagem Docker usando o target "testing"
5. Executa os testes usando o `composer test`
6. Notifica se falhar

### Como eu subi na AWS

Pra criar a máquina na AWS eu documentei todo o processo em um script `create-ec2.sh`.
Depois de criar a máquina, precisei configurar tudo nela. Que também está documentado no script `script-ec2.sh`

### Sobre a integração

Foi implementado um comando Artisan para sincronizar dados da API AlpesOne:

`php artisan app:alpes-one-sync`
