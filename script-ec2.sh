sudo apt-get update -y
sudo apt-get upgrade -y

sudo apt-get install -y \
    ca-certificates \
    curl

sudo apt install -y \
  apt-transport-https \
  ca-certificates \
  curl \
  software-properties-common \
  git

curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update -y
apt-cache policy docker-ce
sudo apt install -y docker-ce

# Adicionar usuário ubuntu ao grupo docker (pra rodar sem sudo)
sudo usermod -aG docker ubuntu

# Criando o projeto na ec2
cd /home/ubuntu
git clone https://github.com/iMatheusPaula/alpesone-challenge.git
cd alpesone-challenge
cp .env.example .env

# Aqui vamos ter que criar o build já, porque não tem php no host
docker compose -f docker-compose.prod.yml build app
docker compose -f docker-compose.prod.yml run --rm app php artisan key:generate

#Configurar manualmente as variáveis de ambiente no .env de banco de dados
vim .env

# APP_ENV=production
# APP_DEBUG=false
# APP_URL=http://...
# DB_PASSWORD=root

# Rodar as migrations depois de configurar o .env
docker compose -f docker-compose.prod.yml run --rm app php artisan migrate --no-interaction --force

# Subir o projeto
docker compose -f docker-compose.prod.yml up -d

# Permissões de storage e cache
sudo chown -R 33:33 storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache

