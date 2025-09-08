==========================================================================================
INSTALAÇÃO
==========================================================================================

Dependências:

"Docker" e "Docker Compose" instalados na maquina host.

---------------

Iniciando:

1 - Clone o repositório para qualquer local em sua maquina.

2 - Via terminal, acesse a pasta do repositorio clonado.

3 - Na raiz do projeto, copiar o arquivo  .env.example para .env, comando abaixo:

    [ cp .env.example .env ]

4 - no terminal, inicie o projeto, executando o comando abaixo:

    [  docker compose up -d  ]

OBS ( composer ): o container hyperf só é ligado após a instalação de todas as dependências do projeto,
     aguarde o composer terminar e o container hyperf iniciar, para que o serviço fique disponível. 

OBS ( hyperf ): aguardar o container hyperf iniciar "completamente", para que o serviço esteja disponivel


PARABÉNS:

sua a aplicação já está funcional localmente.


==========================================================================================
DOCUMENTAÇÃO
==========================================================================================

Para ficilitar nos testes de API, esta em anexo na raiz do projeto o arquivo POSTMAN,
para importar e testar os serviços.

Tecnofit.postman_collection.json

-------------------

Infraestrutura docker ajustada/simplificada para desenvolvimento local.

A instalação dos pacotes ( vendor ), esta automatizada para desenvolvimento local,
a aplicação só inicia após todas as depêndencias estiverem instaladas ( pacotes instaladOs e container composer desligado.),
garantindo assim consistencia de pacotes em todas as maquinas que a aplicação rodar.

-------------------

A aplciação fica disponivel na porta padrão hyperf ( localhost:9501 ),
e na padrão web ( localhost:80 ), a fim de facilitar o teste local.

-------------------

CRON ( Usando nativo Hyperf ):

Foi desenvolvido uma classe do tipo use Hyperf\Command\Command para processamento de saque agendado;
Na vida real, algumas vezes acontece do CRON parar, e ser necessário executar manualmente.
Com isso posso reultilizar ela para processar no Cron, e também processar manualmente chamando o comando abaixo:

php bin/hyperf.php command:process-withdraw


-------------------

A classe App\Application\AccountApplication.php inclui comentários detalhados 
sobre a lógica implementada, facilitando a compreensão e análise por parte do revisor.

-------------------

Para executar os testes, dentro do container hyperf, execute:

vendor/bin/phpunit --testdox

vendor/bin/phpunit --coverage-html coverage

-------------------



/**

Ainda falta:

- Fazer testes unitários

- Fazer observabilidade

 */
