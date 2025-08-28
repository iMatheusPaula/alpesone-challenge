# Gerando uma chave SSH para acessar a instância EC2
aws ec2 create-key-pair --key-name laravel-app-key --query 'KeyMaterial' --output text > laravel-app-key.pem

# Definindo permissões seguras para a chave
chmod 400 laravel-app-key.pem

# Criando um grupo de segurança para a maquina EC2
aws ec2 create-security-group --group-name laravel-sg --description "SG for Laravel API"

# Adicionando regras ao grupo de segurança para permitir tráfego SSH, HTTP e HTTPS
aws ec2 authorize-security-group-ingress --group-name laravel-sg --protocol tcp --port 22 --cidr 0.0.0.0/0
aws ec2 authorize-security-group-ingress --group-name laravel-sg --protocol tcp --port 80 --cidr 0.0.0.0/0
aws ec2 authorize-security-group-ingress --group-name laravel-sg --protocol tcp --port 443 --cidr 0.0.0.0/0

# Obtendo o ID da última imagem Ubuntu 22.04
# --owners 099720109477: Filtra as imagens pertencentes à Canonical (a empresa por trás do Ubuntu).
# --filters "Name=name,Values=ubuntu/images/hvm-ssd/ubuntu-jammy-*": Filtra as imagens cujo nome corresponde ao padrão especificado
# --query 'Images | sort_by(@, &CreationDate) | [-1] | ImageId': Ordena as imagens por data de criação e seleciona a mais recente, retornando seu ID.
# --output text: Formata a saída para retornar apenas o ID da imagem como texto simples
aws ec2 describe-images --owners 099720109477 --filters "Name=name,Values=ubuntu/images/hvm-ssd/ubuntu-jammy-*" --query 'Images | sort_by(@, &CreationDate) | [-1] | ImageId' --output text

# Listando tipos de instância elegíveis para o nível gratuito
aws ec2 describe-instance-types \
  --filters "Name=free-tier-eligible,Values=true" \
  --query "InstanceTypes[*].InstanceType"

# Iniciando uma instância EC2
# ID da imagem obtido no passo anterior
# Tipo de instância obtido no passo anterior
# Nome da chave SSH criada anteriormente
# Nome do grupo de segurança criado anteriormente
# Adicionando uma tag para identificar a instância
aws ec2 run-instances \
  --image-id ami-0bbdd8c17ed981ef9 \
  --instance-type t3.micro \
  --key-name laravel-app-key \
  --security-groups laravel-sg \
  --tag-specifications 'ResourceType=instance,Tags=[{Key=Name,Value=laravel-challenge}]'

#Pegando o ID da instância
aws ec2 describe-instances --query 'Reservations[*].Instances[*].InstanceId' --output text

#Pegando o IP público da instância
aws ec2 describe-instances \
  --instance-ids i-00000000000000000 \
  --query "Reservations[*].Instances[*].PublicIpAddress" \
  --output text

# Alocando um endereço IP elástico (EIP) 👁️ anote o Public IP e o Allocation ID 👁️
aws ec2 allocate-address

# Associando o endereço IP elástico (EIP) à instância EC2
aws ec2 associate-address --instance-id SEU_INSTANCE_ID --allocation-id SEU_ALLOCATION_ID

# Confirmando o IP público da instância EC2 (deve ser o mesmo que o EIP alocado)
aws ec2 describe-instances --instance-ids SEU_INSTANCE_ID --query 'Reservations[].Instances[].PublicIpAddress' --output text

# Caso precise se conectar via SSH - Use o IP público ou EIP
ssh -i laravel-app-key.pem ubuntu@00.000.000.000