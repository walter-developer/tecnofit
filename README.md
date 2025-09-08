# 📘 Instalação e Documentação

---

## 🚀 Instalação

### Dependências
- **Docker**  
- **Docker Compose**  

---

### Iniciando

1. Clone o repositório para qualquer local em sua máquina.
2. Via terminal, acesse a pasta do repositório clonado.
3. Na raiz do projeto, copie o arquivo `.env.example` para `.env`, comando abaixo:

   cp .env.example .env

4. No terminal, inicie o projeto executando o comando abaixo:

   docker compose up -d

⚠️ **OBS (composer):** o container Hyperf só é iniciado após a instalação de todas as dependências do projeto.  
Aguarde o composer terminar e o container Hyperf iniciar para que o serviço fique disponível.  

⚠️ **OBS (Hyperf):** aguarde o container Hyperf iniciar completamente para que o serviço esteja disponível.  

🎉 **PARABÉNS:** Sua aplicação já está funcional localmente.  

---

## 📖 Documentação

Para facilitar os testes da API, está em anexo, na raiz do projeto, o arquivo **POSTMAN**,  
para importar e testar os serviços via aplicação POSTMAN:

Tecnofit.postman_collection.json

---

### 🛠️ Infraestrutura

- Infraestrutura **Docker** ajustada/simplificada para desenvolvimento local.
- A instalação dos pacotes (`vendor`) está **automatizada** para desenvolvimento local.
- A aplicação só inicia após todas as dependências estarem instaladas  
  (pacotes instalados e container composer desligado), garantindo assim consistência de pacotes em todas as máquinas onde a aplicação rodar.

---

### 🌐 Acesso

- A aplicação fica disponível na porta padrão do Hyperf:  
  http://localhost:9501  
- Também na porta padrão web:  
  http://localhost:80  

---

### ⏰ CRON (usando nativo Hyperf)

Foi desenvolvida uma classe do tipo:

use Hyperf\Command\Command;

Essa classe é usada para **processamento de saque agendado**.  

Na vida real, algumas vezes acontece do CRON parar, e ser necessário executar manualmente.  
Com isso, posso reutilizá-la para processar no CRON, e também processar manualmente chamando o comando abaixo:

php bin/hyperf.php command:process-withdraw

---

### 📂 Estrutura de Código

- A classe `App\Application\AccountApplication.php` inclui comentários detalhados sobre a lógica implementada,  
  facilitando a compreensão e análise por parte do revisor.  

---

### 📧 Envio de E-mails

- Para envio de e-mail foi usado o **PHPMailer**, devido aos pacotes de email do Hyperf 3 não estarem funcionando 100% na versão mais recente.  

---

### ✅ Testes

Para executar os testes, dentro do container Hyperf, execute:

vendor/bin/phpunit --testdox
vendor/bin/phpunit --coverage-html coverage

- Testes unitários **100%**  
- Coverage de testes **100%**  

---

### 📌 Observações

1. O container do Hyperf está sendo iniciado com o comando `start`.  
   Caso precise refletir as alterações de código dinamicamente,  
   descomente a linha abaixo no `docker-compose.yml`:

   command: ["server:watch"]

2. Toda a infraestrutura foi pensada para **desenvolvimento local**.  
   Para uma versão de **deploy em PROD**, poderia ser usada uma imagem já empacotada com a aplicação,  
   junto do composer e seu comando de `install`, fazendo assim não ser necessário o mapeamento dos volumes,  
   extensão **xdebug** e container composer.
