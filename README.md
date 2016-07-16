# Cartola Api

Cartola Api é um wrapper da Api oficial do CartolaFC. Com este wrapper você consegue rodar em seu servidor, e fazer requests via ajax do teu site, já que a api oficial bloqueia qualquer requisição ajax que não for do seu domínio (CORS).  

A API é um arquivo único, que fica dentro de `public/index.php`
Jogue o arquivo em seu servidor e voilà.

## Development

Para um rápido desenvolvimento, use o gulp para rodar um servidor php. Mas antes certifique-se que tenha o `php` em sua máquina.

```sh
$ gulp
$ gulp -p <port>
$ gulp -p <port> --open
```

Esse comando servirá a pasta `public` na porta indicada. Passando `--open`, abrirá a página no teu navegador.

## Documentação

Para a documentação, vou supor que a api está rodando em `localhost:4123`

### Rotas sem auth
As rotas a seguir são públicas, isto é, não necessitam de autenticação:

**Status do mercado**  
GET `http://localhost:4123/?p=mercado/status`

**Lista dos jogadores mais escalados**  
GET `http://localhost:4123/?p=mercado/destaques`

**Lista de patrocinadores**  
GET `http://localhost:4123/?p=patrocinadores`

**Lista das rodadas do campeonato (1 até 38)**  
GET `http://localhost:4123/?p=rodadas`

**Próximas partidas do campeonato**  
GET `http://localhost:4123/?p=partidas`

**Lista de clubes**  
GET `http://localhost:4123/?p=clubes`

**Lista de todos os jogadores (retorna todas as informações)**  
GET `http://localhost:4123/?p=atletas/mercado`

**Pontuação da rodada em andamento**  
GET `http://localhost:4123/?p=atletas/pontuados`

**Time que mais pontuou na rodada anterior**  
GET `http://localhost:4123/?p=pos-rodada/destaques`

**Busca geral de times, vai retornar info do time e o slug**  
GET `http://localhost:4123/?p=times&q=<nome-do-time>`

**Busca informações de um time específico, usar o slug do time.**  
GET `http://localhost:4123/?p=time/<slug-do-time>`

**Busca geral de ligas, para consultar uma liga específica é necessário token**  
GET `http://localhost:4123/?p=ligas&q=<nome-da-liga>`

**Lista os esquemas táticos (4-3-3) etc...**  
GET `http://localhost:4123/?p=esquemas`

### Login
Para fazer o login, mande `login` e `password` para `http://localhost:4123/?p=login`, exemplo:  
GET `http://localhost:4123/?p=login&login=meuemail@gmail.com&password=123456`

Se os dados estiverem corretos, retornará um json assim:
```json
{
  "id":"Authenticated",
  "userMessage":"Usuário autenticado com sucesso",
  "glbId": "<token>"
}
```
Este `<token>` é um hash de 215 caractéres. GUARDE ELE!

### Rotas com auth
Depois de realizado o login, use o token nas rotas a seguir:

**Retornar todas as ligas do usuário logado.**  
GET `http://localhost:4123/?p=auth/ligas&token=<token>`

**Retornar informações do time do usuario logado.**  
GET `http://localhost:4123/?p=auth/time&token=<token>`  
GET `http://localhost:4123/?p=auth/time/info&token=<token>`

**Busca informações de uma liga específica, usar o slug da liga.**  
GET `http://localhost:4123/?p=auth/liga/<slug-da-liga>&token=<token>`

**Salvar a escalação do time.**  
POST `http://localhost:4123/?p=auth/time/salvar&token=<token>`
BODY <exemplo>
```json
{
  "esquema": 3,
  "atleta": [
    37788,
    71116,
    39152,
    50427,
    87225,
    62009,
    81682,
    87863,
    78435,
    68930,
    90651,
    62136
  ]
}
```

**Muita atenção:** o servidor do cartola valida a posição dos IDS dos usuarios, com o esquema enviado. Caso não seja de acordo, ele vai retornar um erro. (Por exemplo: enviar 3 atacantes numa posição de 4-4-2, ou enviar 4 atacantes em 4-3-3, etc)
