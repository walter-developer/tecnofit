Infraestrutura docker ajustada/simplificada para desenvolvimento local.

A instalação dos pacotes ( vendor ), esta automatizada para desenvolvimento local,
a aplicação só inicia após todas as depêndencias estiverem instaladas ( pacotes instaladas e container composer desligado.),
garantindo assim consistencia de pacotes em todas as maquinas que a aplicação rodar.

A aplciação fica disponivel na porta padrão hyperf ( localhost:9501 ),
e na padrão web ( localhost:80 ), a fim de facilitar o teste local.


CRON ( Usando nativo Hyperf ):

Foi desenvolvido uma classe do tipo use Hyperf\Command\Command para processamento de saque agendado;
Na vida real, algumas vezes acontece do CRON parar, e ser necessário executar manualmente.
Com isso posso reultilizar ela para processar no Cron, e também processar manualmente chamando o comando abaixo:

php bin/hyperf.php command:process-withdraw



/**

Ainda falta:


- Fazer envio de email.

- Fazer testes unitários

- Fazer observabilidade

 */
