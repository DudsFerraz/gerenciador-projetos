***
# Escopo do Sistema e Funcionalidades

Este documento detalha as funcionalidades do sistema de gestão de projetos, dividido entre o Produto Mínimo Viável (MVP) — essencial para o lançamento — e o roadmap de melhorias contínuas (Features Futuras).

## 1. MVP (Minimum Viable Product)

O núcleo do sistema focado em resolver a organização básica de projetos, tarefas e o acompanhamento de reuniões.
---

### 1.1. Gestão de Projetos (Projects)

> O sistema permite a criação e centralização de projetos, vinculando-os aos usuários responsáveis da equipe. Cada projeto possui um status de acompanhamento ('DEVELOPMENT', 'PRODUCTION', 'MIGRATED'...) para facilitar a identificação de sua etapa atual no ciclo de vida do desenvolvimento.

* **Implementação:** Tabela `projects` com CRUD padrão no Laravel e tabela pivô `project_user`.
* **Prós:** Simplicidade inicial; resolve o problema imediato de organização.
* **Contras:** Sem a tabela `documents` no MVP, o contexto detalhado do projeto precisará ficar temporariamente na descrição básica.
---

### 1.2. Gestão de Tarefas (Tasks)

> Fornece um sistema de cards para mapear e acompanhar o trabalho. Cada tarefa conta com definição de prioridade, status ('TO_DO', 'IN_PROGRESS', 'IN_REVIEW'...), datas de início e entrega, além de labels ('FEATURE','FIX','DOC'...).

* **Implementação:** Tabela `tasks` vinculadas aos projetos, com tabela pivô `task_user` para atribuição de múltiplos responsáveis.
* **Prós:** Permite acompanhamento granular do progresso da equipe.
* **Contras:** Sem aninhamento (subtasks) no MVP.
---

### 1.3. Reuniões e Atualizações de Status (Meetings & Status Updates)

> Gerentes de projeto podem submeter atualizações de status referentes aos seus projetos diretamente para a pauta de uma reunião programada, consolidando o histórico de evolução e as decisões em um único local.

* **Implementação:** Tabelas `meetings` e `status_updates`. O Laravel fará o relacionamento para agrupar as atualizações por reunião.
* **Prós:** Regsitra o histórico de evolução dos projetos ao longo do tempo.
* **Contras:** Depende da adesão dos usuários; se os gerentes não submeterem `status_updates`, a reunião fica vazia no sistema.
---

### 1.4. Autenticação e Usuários (Users)

> Controle de acesso seguro e unificado, permitindo que os usuários acessem a plataforma utilizando as credenciais da rede da instituição. Elimina a necessidade de criação de novos cadastros manuais ou gerenciamento de múltiplas senhas pelos colaboradores.

* **Implementação:** Utilização do pacote `senha-unica-socialite`.
* **Prós:** O usuário não precisa decorar uma nova senha; a gestão de identidades é 'terceirizada' e segura.
---

## 2. Features Futuras (Roadmap)

Funcionalidades planejadas para expandir a usabilidade, governança e integração da plataforma após a consolidação do MVP.

>A  lista abaixo é ordenada por prioridade, ou seja, os últimos itens são considerados de baixo impacto e relevância.

---

### 2.1. Documentação Interna Nativa (Docs)

> **Problema:** Sem uma estrutura dedicada, o contexto e os requisitos de um projeto ficam restritos a um texto simples (descrição), limitando a formatação e fragmentando a informação em serviços externos (como Google Docs).
>
> **Solução:** Tratar os documentos como entidades independentes em vez de apenas um campo de texto em `projects`.

* **Implementação:** Tabela própria de `documents` relacionada aos projetos (`project_id`), preparada para receber dados de um editor *rich text*.
* **Prós:** Cria uma base de conhecimento interna e expansível.
* **Contras:** Exige a integração e configuração de bibliotecas de edição de texto rico no front-end.

---

### 2.2. Mídias em Tarefas (Fotos e Vídeos)

> **Problema:** Bugs visuais ou fluxos complexos são difíceis de explicar apenas com texto.
>
> **Solução:** Suporte nativo para anexar arquivos de mídia nas descrições e comentários das tarefas.

* **Implementação:** Integração com Storage do Laravel (S3 ou disco local) e componentes de *drag-and-drop* no front-end.
* **Prós:** Aumenta muito a clareza da comunicação técnica.
* **Contras:** Complexidade em armazenar os arquivos e necessidade de implementar limpeza periódica de arquivos não utilizados.

---

### 2.3. Dashboards Analíticos e Calendário do Projeto

> **Problema:** À medida que o volume de trabalho cresce, acompanhar a saúde geral do projeto analisando apenas listas de tarefas torna-se inviável. Fica difícil visualizar os marcos temporais e a distribuição do esforço da equipe sem uma visão macro consolidada.
>
> **Solução:** Telas gerenciais específicas dentro de cada projeto. Essa visualização deve incluir gráficos de progresso geral, porcentagem de contribuição por usuário, uma aba de calendário exibindo os deadlines das tarefas e as datas de reuniões atreladas àquele contexto.

* **Implementação:** Criação de um modelo ou tabela independente de `dashboards` atrelada aos projetos para gerenciar preferências de exibição. No backend, uso de consultas de agregação robustas com o Eloquent. No front-end, integração de bibliotecas de gráficos e de calendário para renderizar os dados de forma interativa.

* **Prós:** Fornece uma visão executiva excelente e transparente, facilitando o acompanhamento de métricas e do cronograma para o seu chefe e outros gestores que utilizarão a plataforma na empresa.

* **Contras:** O cálculo constante de porcentagens e a agregação dessas estatísticas podem sobrecarregar o banco de dados. Necessário desenhar estratégias de *cache* ou criar tabelas sumarizadas de consolidação no backend conforme o histórico do sistema interno da empresa for crescendo.

---

### 2.4. Dashboard e Calendário Pessoal do Usuário

> **Problema:** Usuários perdem tempo navegando pelas *views* de múltiplos projetos para descobrir quais são suas pendências do dia.
>
> **Solução:** Uma tela inicial (dashboard) focada no indivíduo, contendo apenas as tarefas atribuídas a ele e uma aba de calendário exibindo deadlines e datas de reuniões importantes.

* **Implementação:** Resolução voltada em sua maior parte para o front-end (usando bibliotecas de calendário), com o backend servindo *queries* simples filtradas por `user_id`.
* **Prós:** Baixa complexidade no backend e alto impacto direto na organização pessoal.
* **Contras:** A lógica do front-end com calendário exige cuidado rigoroso no tratamento de fusos horários (*timezones*).

---

### 2.5. Integração Bidirecional com GitHub

> **Problema:** Desenvolvedores duplicam trabalho atualizando o GitHub (Issues/PRs) e o sistema interno de tarefas.
>
> **Solução:** Sincronização automática. Fechar uma Issue no GitHub fecha a Task no sistema interno e vice-versa.

* **Implementação:** Utilização da API REST oficial do GitHub (ou Webhooks). O backend do Laravel precisará de rotas para escutar os *payloads* do GitHub.
* **Prós:** Redução drástica de atrito e trabalho manual para os devs. Provável aumento de adesão ao sistema interno, e mesmo que a equipe esteja criando issues no GitHub o sistema está ciente disso e é atualizado.
* **Contras:** Lógica complexa de mapeamento de IDs e tratamento de falhas de rede entre os dois sistemas.

---

### 2.6. Organização Estrutural (Pastas, Listas e Subtasks)

> **Problema:** À medida que a ferramenta cresce, um projeto contendo centenas de tarefas se torna inavegável.
>
> **Solução:** Permitir agrupamento de tarefas em listas, projetos em pastas com visibilidade controlada, e tarefas aninhadas.

* **Implementação:** Tabelas `folders` (com hierarquia pai/filho), `lists` e self-joins na tabela `tasks` (coluna `parent_id`).
* **Prós:** Organização comparável a ferramentas de ponta do mercado (semelhante ao Google Drive).
* **Contras:** Consultas SQL complexas para buscar hierarquias profundas e riscos de performance sem cache adequado.

---

### 2.7. Múltiplas Visões (View de Dev vs. View Administrativa)

> **Problema:** Softwares como Jira são robustos, mas intimidam usuários não-técnicos. Equanto isso, softwares simplistas não atendem às métricas necessárias para desenvolvedores.
>
> **Solução:** Fornecer interfaces adaptáveis baseadas no perfil do usuário. Baseado no perfil do usuário devem ser renderizados componentes visuais diferentes no front-end.

* **Prós:** Maximiza a adesão em diferentes setores da instituição.
* **Contras:** Aumenta significativamente a carga de trabalho no desenvolvimento do front-end (manutenção de duas interfaces).

---

### 2.8. Trilha de Auditoria Avançada (Logs)

> **Problema:** O MVP possui apenas o último usuário que modificou um registro, causando "amnésia de estado" sobre o histórico do projeto.
>
> **Solução:** Registrar todas as mudanças de campos críticos (quem mudou, quando, valor antigo e valor novo). Um tipo de versionamento básico.

* **Implementação:** Tabela `audit_logs` acionada por Model Events do Laravel (pacotes como `spatie/laravel-activitylog`).
* **Prós:** Rastreabilidade completa, segurança e transparência.
* **Contras:** Implementação relativamente trabalhosa e alto consumo de armazenamento no banco de dados a longo prazo.

---

### 2.9. Central de Comunicação (Inbox, Menções e E-mails)

> **Problema:** A comunicação fica dispersa e os usuários não sabem quando são demandados.
>
> **Solução:** Notificações in-app (Inbox central), envio de e-mails para eventos críticos e capacidade de mencionar pessoas (@masaki) nos documentos.

* **Implementação:** Laravel Notifications (canais database e mail) e parser de texto rico no front-end/back-end para detectar `@usernames`.
* **Prós:** Mantém a equipe engajada e responsiva dentro do ecossistema do sistema.
* **Contras:** Requer configuração de infraestrutura de e-mail SMTP (Uma integração completa com o github mata esse problema, já que o GitHub se encarregaria de enviar emails por nós).

---

### 2.10. Busca Global e Filtros Avançados

> **Problema:** Encontrar tarefas ou documentos específicos se torna exaustivo quando o sistema escala e o número de cards aumenta.
>
> **Solução:** Implementar barras de pesquisa avançada e a capacidade de combinar filtros nas listas de tarefas (por responsáveis, status, prioridade, data limite, label ou quem criou).

* **Implementação:** Construção de *Query Scopes* no backend para processamento dos filtros combinados e formulários dinâmicos na interface.
* **Prós:** Indispensável para a manutenção da produtividade e navegabilidade do software.
* **Contras:** Combinações de filtros complexos podem exigir que a estrutura do banco possua índices bem elaborados para não gerar lentidão nas *queries*.

---

### 2.11. Navegação por Atalhos de Teclado

> **Problema:** A dependência exclusiva do uso do mouse quebra o estado de *flow* (fluxo) e desacelera o trabalho, principalmente para desenvolvedores.
>
> **Solução:** Adicionar *shortcuts* (atalhos de teclado) globais para ações comuns da plataforma, como criar uma tarefa nova, focar na barra de busca ou atribuir algo a si mesmo.

* **Implementação:** Configuração de *event listeners* globais e bibliotecas gerenciadoras de teclas de atalho no front-end.
* **Prós:** Aumenta drasticamente a sensação de rapidez e melhora muito a experiência (UX) de *power users*.
* **Contras:** Necessidade de gerenciar bem os conflitos no front-end (por exemplo, garantir que um atalho não seja disparado acidentalmente enquanto o usuário digita um comentário em uma tarefa).

***
