Infraestrutura docker ajustada/simplificada para desenvolvimento local.

A instalação dos pacotes ( vendor ), esta automatizada para desenvolvimento local,
a aplicação só inicia após todas as depêndencias estiverem instaladas ( pacotes instaladas e container composer desligado.),
garantindo assim consistencia de pacotes em todas as maquinas que a aplicação rodar.

A aplciação fica disponivel na porta padrão hyperf ( localhost:9501 ),
e na padrão web ( localhost:80 ), a fim de facilitar o teste local.


/**

Ainda falta:

- Finalizar de integrar as camadas hexagonais do sistema
  
- Validar o saldo nunca ser negativo e menor que o valor do saque

- Subitrair o valor de saque do saldo do cliente

- Fazer o cron que fica realizando o pagamento de saque agendado

- Fazer testes unitários

- Fazer observabilidade

 */
