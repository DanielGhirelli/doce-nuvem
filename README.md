# 🧁 Doce Nuvem

A ideia foi fazer uma lojinha online de cupcakes. O cliente cria a conta, escolhe os sabores no cardápio, monta o carrinho e fecha o pedido escolhendo a forma de pagamento. Depois ele consegue acompanhar se o pedido ainda está sendo preparado ou se já foi entregue. Do lado da loja, o administrador vê todos os pedidos que chegaram e vai marcando conforme entrega.

## O que usei

- PHP 8.3 com Apache
- MySQL 8.4
- HTML, CSS e um pouco de jQuery no carrinho
- Docker para subir tudo junto sem precisar instalar nada na máquina

## Como rodar

Precisa ter o Docker instalado. Na pasta do projeto:

```bash
docker compose up --build
```

Depois é só abrir <http://localhost:8088>.

O banco já sobe criado, com 6 cupcakes cadastrados e um usuário administrador:

- **usuário:** `admin`
- **senha:** `admin123`

Para testar como cliente, é só clicar em **Criar conta** na tela de login.

Se quiser zerar o banco e começar de novo:

```bash
docker compose down -v
docker compose up --build
```

## Como o projeto está organizado

```
public/             páginas do site
  index.php           cardápio
  register.php        cadastro
  login.php           login
  logout.php          sair
  checkout.php        carrinho e finalização do pedido
  profile.php         perfil e meus pedidos
  admin.php           painel de pedidos (só admin)
  css/ js/ img/
src/                conexão com o banco, login e funções que uso em várias páginas
views/              cabeçalho e rodapé
scripts/schema.sql  criação das tabelas e dados iniciais
```

O banco tem quatro tabelas:

- `users`: clientes e administradores. A senha fica salva com hash, nunca em texto puro.
- `cupcakes`: os produtos do cardápio.
- `orders`: os pedidos, com forma de pagamento, total e status.
- `order_items`: o que foi pedido em cada pedido. Guardo o preço da hora da compra, assim se o preço mudar depois o histórico não muda junto.

## Fluxo do usuário

```
Criar conta → Login → Cardápio → Carrinho e pagamento → Pedido confirmado → Acompanhar status
```

E do lado do admin: vê o pedido no painel → entrega → marca como "Entregue" → o cliente vê a mudança no perfil dele.

## Histórias de usuário

### ID 1 – Cadastro de Usuário

**Requerente:** Equipe de Desenvolvimento
**Ação:** O usuário deve ser capaz de se cadastrar no aplicativo.

**Critérios de aceitação:**
- CA#1: O sistema deve pedir usuário, nome completo, endereço de entrega e senha (digitada duas vezes).
- CA#2: O sistema deve validar os campos antes de salvar.

**Regras de negócio:**
- RN#1: O nome de usuário não pode se repetir.
- RN#2: A senha precisa ter pelo menos 6 caracteres.

**Requisito não funcional:**
- RNF#1: O cadastro deve ser concluído em até 5 segundos.

**Prioridade:** A
**Pontos de história:** 3

---

### ID 2 – Login de Usuário

**Requerente:** Equipe de Desenvolvimento
**Ação:** O usuário deve ser capaz de fazer login no aplicativo.

**Critérios de aceitação:**
- CA#1: O sistema deve pedir usuário e senha.
- CA#2: O sistema deve avisar quando o usuário ou a senha estiverem errados.

**Regras de negócio:**
- RN#1: O usuário precisa estar cadastrado para entrar.
- RN#2: O administrador cai direto no painel de pedidos e o cliente cai no cardápio.

**Requisito não funcional:**
- RNF#1: O login deve ser processado em até 5 segundos.

**Prioridade:** A
**Pontos de história:** 2

---

### ID 3 – Visualização do Cardápio

**Requerente:** Equipe de Vendas
**Ação:** O usuário deve ser capaz de ver os cupcakes disponíveis.

**Critérios de aceitação:**
- CA#1: O sistema deve mostrar a foto, o nome, a descrição e o preço de cada cupcake.

**Regras de negócio:**
- RN#1: Todos os cupcakes cadastrados devem aparecer no cardápio.

**Requisito não funcional:**
- RNF#1: As imagens devem carregar em até 5 segundos.

**Prioridade:** A
**Pontos de história:** 5

---

### ID 4 – Adicionar Cupcakes ao Carrinho

**Requerente:** Equipe de Vendas
**Ação:** O usuário deve ser capaz de colocar cupcakes no carrinho.

**Critérios de aceitação:**
- CA#1: O sistema deve permitir escolher a quantidade de cada cupcake.
- CA#2: O contador do carrinho no topo da página deve atualizar na hora.

**Regras de negócio:**
- RN#1: O carrinho fica salvo no navegador até o pedido ser finalizado.

**Requisito não funcional:**
- RNF#1: A atualização do carrinho deve ser instantânea.

**Prioridade:** A
**Pontos de história:** 3

---

### ID 5 – Remover Cupcakes do Carrinho

**Requerente:** Equipe de Vendas
**Ação:** O usuário deve ser capaz de tirar cupcakes do carrinho.

**Critérios de aceitação:**
- CA#1: O sistema deve permitir diminuir a quantidade ou tirar o item de vez.
- CA#2: O total deve ser recalculado na hora.

**Regras de negócio:**
- RN#1: Quando a quantidade chega a zero, o item sai do carrinho.

**Requisito não funcional:**
- RNF#1: A atualização do carrinho deve ser instantânea.

**Prioridade:** A
**Pontos de história:** 2

---

### ID 6 – Finalização do Pedido

**Requerente:** Equipe de Vendas
**Ação:** O usuário deve ser capaz de finalizar a compra dos cupcakes que estão no carrinho.

**Critérios de aceitação:**
- CA#1: O sistema deve mostrar o endereço de entrega e pedir a forma de pagamento (dinheiro, Pix ou cartão).
- CA#2: O sistema deve mostrar a confirmação com o número do pedido, o total e a forma de pagamento.

**Regras de negócio:**
- RN#1: O preço é sempre calculado pelo servidor com os valores do banco, e não pelo que vem do navegador.
- RN#2: O pedido só é salvo se todos os itens forem salvos junto.

**Requisito não funcional:**
- RNF#1: A confirmação deve aparecer em até 5 segundos.

**Prioridade:** A
**Pontos de história:** 5

---

### ID 7 – Acompanhamento do Pedido

**Requerente:** Equipe de Logística
**Ação:** O usuário deve ser capaz de acompanhar o status do pedido depois da compra.

**Critérios de aceitação:**
- CA#1: O sistema deve mostrar se o pedido está "Preparando para entrega" ou se já foi "Entregue".

**Regras de negócio:**
- RN#1: Quem muda o status é o administrador da loja.

**Requisito não funcional:**
- RNF#1: O novo status deve aparecer assim que o usuário atualizar a página.

**Prioridade:** A
**Pontos de história:** 4

---

### ID 8 – Histórico de Pedidos

**Requerente:** Equipe de Vendas
**Ação:** O usuário deve ser capaz de ver os pedidos que já fez.

**Critérios de aceitação:**
- CA#1: O sistema deve listar todos os pedidos com data, itens, total, forma de pagamento e status.

**Regras de negócio:**
- RN#1: O valor mostrado é o que foi pago na hora da compra.

**Requisito não funcional:**
- RNF#1: Os pedidos devem carregar em até 2 segundos.

**Prioridade:** A
**Pontos de história:** 3

---

### ID 9 – Perfil de Usuário

**Requerente:** Equipe de Desenvolvimento
**Ação:** O usuário deve ser capaz de editar o seu perfil.

**Critérios de aceitação:**
- CA#1: O sistema deve permitir trocar o endereço de entrega.
- CA#2: O sistema deve avisar que a alteração foi salva.

**Regras de negócio:**
- RN#1: O novo endereço vale para os próximos pedidos.

**Requisito não funcional:**
- RNF#1: As alterações devem ser salvas em até 5 segundos.

**Prioridade:** A
**Pontos de história:** 2

---

### ID 10 – Painel de Pedidos do Administrador

**Requerente:** Equipe de Logística
**Ação:** O administrador deve ser capaz de ver todos os pedidos e marcar quais já foram entregues.

**Critérios de aceitação:**
- CA#1: O sistema deve listar os pedidos com cliente, endereço, itens, pagamento, total e status.
- CA#2: O sistema deve mostrar quantos pedidos chegaram, quantos faltam entregar e o faturamento.

**Regras de negócio:**
- RN#1: Só o administrador pode acessar o painel.

**Requisito não funcional:**
- RNF#1: O painel deve carregar em até 2 segundos.

**Prioridade:** A
**Pontos de história:** 3

## Backlog do produto

| ID | História do usuário                  | Estimativa em pontos | Prioridade |
|----|--------------------------------------|----------------------|------------|
| 1  | Cadastro de Usuário                  | 3                    | A          |
| 2  | Login de Usuário                     | 2                    | A          |
| 3  | Visualização do Cardápio             | 5                    | A          |
| 4  | Adicionar Cupcakes ao Carrinho       | 3                    | A          |
| 5  | Remover Cupcakes do Carrinho         | 2                    | A          |
| 6  | Finalização do Pedido                | 5                    | A          |
| 7  | Acompanhamento do Pedido             | 4                    | A          |
| 8  | Histórico de Pedidos                 | 3                    | A          |
| 9  | Perfil de Usuário                    | 2                    | A          |
| 10 | Painel de Pedidos do Administrador   | 3                    | A          |
