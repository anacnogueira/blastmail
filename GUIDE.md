# 📧 Guia Completo do BlastMail - Para Desenvolvedores Júnior

Bem-vindo ao **BlastMail**! Este guia foi criado para você entender como o projeto funciona, desde sua estrutura até como os emails são enviados em massa.

---

## 📚 Índice

1. [O que é BlastMail?](#o-que-é-blastmail)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Estrutura de Pastas](#estrutura-de-pastas)
4. [Banco de Dados](#banco-de-dados)
5. [Os Modelos (Models)](#os-modelos-models)
6. [Como Funciona o Fluxo de Emails](#como-funciona-o-fluxo-de-emails)
7. [Conceitos Importantes](#conceitos-importantes)
8. [Primeiros Passos](#primeiros-passos)
9. [Dicas e Boas Práticas](#dicas-e-boas-práticas)

---

## O que é BlastMail?

**BlastMail** é uma aplicação web para gerenciar e enviar campanhas de email em massa. Pense nela como uma ferramenta similar ao Mailchimp ou Brevo, mas construída do zero com Laravel.

### Funcionalidades principais:

- ✅ Criar listas de emails (segmentadas)
- ✅ Gerenciar subscribers (inscritos)
- ✅ Criar templates de email
- ✅ Criar campanhas e agendar envio
- ✅ Rastrear aberturas e cliques nos emails
- ✅ Enviar emails em fila (background jobs)

---

## Stack Tecnológico

| Tecnologia       | Versão  | Função                     |
| ---------------- | ------- | -------------------------- |
| **Laravel**      | 13.0    | Framework backend          |
| **PHP**          | 8.3+    | Linguagem do servidor      |
| **Laravel Sail** | 1.56    | Ambiente Docker            |
| **Vite**         | 8.0     | Bundler de assets (CSS/JS) |
| **Tailwind CSS** | 3.4.19  | Framework CSS              |
| **Alpine.js**    | 3.4.2   | Interatividade frontend    |
| **PHPUnit**      | 12.5.12 | Testes automatizados       |
| **SQLite/MySQL** | -       | Banco de dados             |

---

## Estrutura de Pastas

```
blastmail/
│
├── 📁 app/                          # Código principal da aplicação
│   ├── Http/
│   │   ├── Controllers/             # Controladores (lógica das rotas)
│   │   │   ├── CampaignController.php
│   │   │   ├── EmailListController.php
│   │   │   ├── SubscriberController.php
│   │   │   ├── TemplateController.php
│   │   │   ├── TrackingController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/              # Middleware (filtros de requisição)
│   │   └── Requests/                # Form Requests (validação)
│   │
│   ├── Jobs/                        # Jobs assíncronos (filas)
│   │   ├── SendEmailCampaignJob.php  # Job principal de envio
│   │   └── SendEmailsCampaign.php
│   │
│   ├── Mail/                        # Mailable classes (templates de email)
│   │   └── EmailCampaign.php        # Classe que formata o email
│   │
│   ├── Models/                      # Modelos Eloquent (Banco de dados)
│   │   ├── Campaign.php             # Modelo de Campanha
│   │   ├── CampaignEmail.php        # Modelo de Email enviado
│   │   ├── EmailList.php            # Modelo de Lista de Emails
│   │   ├── Subscriber.php           # Modelo de Inscrito
│   │   ├── Template.php             # Modelo de Template
│   │   └── User.php                 # Modelo de Usuário
│   │
│   └── Providers/                   # Service Providers (configuração)
│       └── AppServiceProvider.php
│
├── 📁 database/                     # Banco de dados
│   ├── migrations/                  # Migrações (versionamento BD)
│   ├── factories/                   # Factory (dados de teste)
│   └── seeders/                     # Seeders (popular BD)
│
├── 📁 routes/                       # Definição de rotas
│   ├── web.php                      # Rotas da web
│   ├── auth.php                     # Rotas de autenticação
│   └── console.php                  # Comandos Artisan
│
├── 📁 resources/
│   ├── views/                       # Templates Blade (HTML)
│   │   ├── campaigns/               # Views de campanhas
│   │   ├── email-lists/             # Views de listas
│   │   ├── subscribers/             # Views de inscritos
│   │   ├── templates/               # Views de templates
│   │   ├── mail/                    # Templates de email
│   │   └── profile/                 # Views de perfil
│   │
│   ├── css/                         # Arquivos CSS
│   └── js/                          # Arquivos JavaScript
│
├── 📁 config/                       # Arquivos de configuração
│   ├── app.php
│   ├── mail.php                     # Configuração de email
│   ├── queue.php                    # Configuração de filas
│   ├── database.php                 # Configuração de BD
│   └── ...
│
├── 📁 public/                       # Arquivos públicos
│   ├── index.php                    # Ponto de entrada
│   └── build/                       # Assets compilados
│
├── 📁 storage/                      # Arquivos de armazenamento
│   ├── app/                         # Uploads de usuários
│   ├── logs/                        # Logs da aplicação
│   └── framework/                   # Cache, sessions
│
├── 📁 tests/                        # Testes automatizados
│   ├── Feature/                     # Testes de funcionalidade
│   └── Unit/                        # Testes unitários
│
├── composer.json                    # Dependências PHP
├── package.json                     # Dependências Node.js
├── .env                             # Variáveis de ambiente
├── .env.example                     # Template de .env
└── artisan                          # CLI do Laravel
```

---

## Banco de Dados

### 📊 Estrutura das Tabelas

O BlastMail usa 6 tabelas principais + tabelas do Laravel (users, migrations, jobs).

#### 1️⃣ **users** (Usuários)

Tabela padrão do Laravel. Armazena os usuários que podem acessar o sistema.

```
+----+--------+------------------------+---------------------+
| id | name   | email                  | email_verified_at   |
+----+--------+------------------------+---------------------+
| 1  | João   | joao@example.com       | 2026-05-22 10:30:00 |
+----+--------+------------------------+---------------------+
```

**Campos importantes:**

- `id`: Identificador único (chave primária)
- `name`: Nome do usuário
- `email`: Email único do usuário
- `password`: Senha hasheada
- `email_verified_at`: Data de verificação de email
- `created_at`, `updated_at`: Timestamps

#### 2️⃣ **email_lists** (Listas de Emails)

Agrupa subscribers em listas. Uma lista é uma segmentação de contatos (ex: "Clientes Premium", "Newsletter Geral").

```
+----+---------------+-----------+-----------+
| id | created_at    | updated_at| deleted_at|
+----+---------------+-----------+-----------+
| 1  | 2026-04-09... | 2026-05.. | NULL      |
+----+---------------+-----------+-----------+
```

**Campos importantes:**

- `id`: Identificador único
- `deleted_at`: Campo de soft delete (abordado mais abaixo)
- Timestamps: `created_at`, `updated_at`

#### 3️⃣ **subscribers** (Inscritos)

Armazena os contatos que receberão os emails.

```
+----+---------------+---------+-------------------+-----------+
| id | email_list_id | name    | email             | deleted_at|
+----+---------------+---------+-------------------+-----------+
| 1  | 1             | Maria   | maria@example.com | NULL      |
| 2  | 1             | Pedro   | pedro@example.com | NULL      |
+----+---------------+---------+-------------------+-----------+
```

**Campos importantes:**

- `id`: Identificador único
- `email_list_id`: Referência para qual lista este subscriber pertence (chave estrangeira)
- `name`: Nome do subscriber
- `email`: Email do subscriber
- `deleted_at`: Soft delete

#### 4️⃣ **templates** (Templates de Email)

Armazena templates reutilizáveis de emails.

```
+----+------------------+---------------------------+-----------+
| id | name             | body                      | deleted_at|
+----+------------------+---------------------------+-----------+
| 1  | Boas-vindas      | <h1>Bem-vindo!</h1>...   | NULL      |
| 2  | Newsletter       | <h1>Newsletter</h1>...   | NULL      |
+----+------------------+---------------------------+-----------+
```

**Campos importantes:**

- `id`: Identificador único
- `name`: Nome descritivo do template
- `body`: Conteúdo HTML do template
- `deleted_at`: Soft delete

#### 5️⃣ **campaigns** (Campanhas)

Armazena as campanhas de email (cada envio em massa).

```
+----+----------+---------+---------------+-------------+----------+------------------+
| id | name     | subject | email_list_id | template_id | track_.. | send_at          |
+----+----------+---------+---------------+-------------+----------+------------------+
| 1  | Promo 1  | Desconto| 1             | 1           | 1        | 2026-05-25 14:00 |
+----+----------+---------+---------------+-------------+----------+------------------+
```

**Campos importantes:**

- `id`: Identificador único
- `name`: Nome da campanha (interno)
- `subject`: Assunto do email
- `email_list_id`: Qual lista vai receber (chave estrangeira)
- `template_id`: Template usado (chave estrangeira)
- `body`: Conteúdo personalizado (anula o template se preenchido)
- `track_open`: Se deve rastrear aberturas (boolean)
- `track_click`: Se deve rastrear cliques (boolean)
- `send_at`: Data/hora agendada para envio
- `deleted_at`: Soft delete

#### 6️⃣ **campaign_emails** (Emails Enviados)

Registro de cada email enviado. Esta é a tabela mais importante para análise!

```
+----+-------------+---------------+----------+--------+-----------+-----------+
| id | campaign_id | subscriber_id | sent_at  | clicks | openings  | created_at|
+----+-------------+---------------+----------+--------+-----------+-----------+
| 1  | 1           | 1             | 2026-... | 2      | 5         | 2026-..   |
| 2  | 1           | 2             | 2026-... | 0      | 1         | 2026-..   |
+----+-------------+---------------+----------+--------+-----------+-----------+
```

**Campos importantes:**

- `id`: Identificador único
- `campaign_id`: Qual campanha este email faz parte (chave estrangeira)
- `subscriber_id`: Para qual subscriber foi enviado (chave estrangeira)
- `sent_at`: Data/hora de envio real
- `clicks`: Número de cliques rastreados
- `openings`: Número de aberturas rastreadas

### 🔗 Relacionamentos Entre Tabelas

```
User (1) ─────── (N) Campaign
                  │
                  └──> EmailList (1) ─────── (N) Subscriber
                  │
                  └──> Template (1)

Campaign (1) ─────── (N) CampaignEmail (N) ────── (1) Subscriber
```

**Explicação:**

- Um usuário pode ter várias campanhas
- Uma campanha usa uma lista de email e um template
- Uma campanha envia vários emails (um por subscriber)
- Cada email enviado rastreia dados do subscriber

---

## Os Modelos (Models)

No Laravel, cada tabela tem um "Model" correspondente. O Model é uma classe PHP que representa a tabela e facilita a interação com o banco.

### User.php

```php
class User extends Authenticatable
```

- Herda de `Authenticatable` (integração com autenticação do Laravel)
- Usa `HasFactory` (para gerar dados de teste)
- Campos fillable: `name`, `email`, `password`
- Implementa `MustVerifyEmail`

### EmailList.php

```php
class EmailList extends Model
{
    use HasFactory, SoftDeletes;

    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class);
    }
}
```

- `SoftDeletes`: Não deleta de verdade, apenas marca como deletado
- Relacionamento: Uma EmailList tem muitos Subscribers

### Campaign.php

```php
class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    public function emailList(): BelongsTo { ... }
    public function emails(): HasMany { ... }
}
```

- `BelongsTo`: Pertence a uma EmailList
- `HasMany`: Tem muitos CampaignEmails

### Subscriber.php

```php
class Subscriber extends Model
{
    use HasFactory, SoftDeletes;

    public function emailList(): BelongsTo { ... }
}
```

- Pertence a uma EmailList

### Template.php

```php
class Template extends Model
{
    use HasFactory, SoftDeletes;
}
```

- Simples, apenas armazena templates

### CampaignEmail.php

```php
class CampaignEmail extends Model
{
    use HasFactory;

    public function campaign(): BelongsTo { ... }
    public function subscriber(): BelongsTo { ... }

    public function scopeStatistics(Builder $query)
    {
        return $query->selectRaw("
            sum(openings) as total_openings,
            count(subscriber_id) as total_subscribers,
            ...
        ");
    }
}
```

- Armazena dados de cada email enviado
- Tem um método `statistics()` que calcula métricas da campanha

---

## Como Funciona o Fluxo de Emails

### 🔄 Passo a Passo Completo

```
1. USUÁRIO CRIA CAMPANHA
   └─> Preenche formulário (nome, assunto, template, lista de email)
   └─> Escolhe data/hora de envio

2. CAMPANHA É SALVA NO BANCO
   └─> Registro criado na tabela "campaigns"

3. USUÁRIO CONFIRMA ENVIO
   └─> Um "Job" é criado para cada subscriber da lista

4. JOBS ENTRAM NA FILA
   └─> Cada job: SendEmailCampaignJob(campaign, subscriber)
   └─> Aguardam na fila para serem processados

5. WORKER PROCESSA JOBS
   └─> Comando: php artisan queue:listen
   └─> Worker pega um job da fila

6. JOB EXECUTA (SendEmailCampaignJob.php)
   ├─> Cria um registro em campaign_emails
   ├─> Define sent_at = agora
   └─> Envia email com Mail::to()->later()

7. EMAIL ENVIADO
   └─> Sistema de fila (Queue) envia o email

8. SUBSCRIBER RECEBE EMAIL
   └─> Email contém pixels de rastreamento
   └─> Links possuem IDs para rastreamento

9. RASTREAMENTO
   ├─> Se subscriber abrir: GET /t/{email}/o (openings++)
   ├─> Se clicar em link: GET /t/{email}/c (clicks++)
   └─> TrackingController registra as ações
```

### 📂 Código Importante

#### SendEmailCampaignJob.php

```php
class SendEmailCampaignJob implements ShouldQueue
{
    public function __construct(
        public Campaign $campaign,
        public Subscriber $subscriber,
    ) {}

    public function handle(): void
    {
        // 1. Cria registro do email enviado
        $email = CampaignEmail::query()->create([
            'campaign_id' => $this->campaign->id,
            'subscriber_id' => $this->subscriber->id,
            'sent_at' => $this->campaign->sent_at,
        ]);

        // 2. Envia o email depois de $campaign->send_at
        Mail::to($this->subscriber->email)
            ->later($this->campaign->send_at, new EmailCampaign($this->campaign, $email));
    }
}
```

#### EmailCampaign.php (Mailable)

```php
class EmailCampaign extends Mailable
{
    public function __construct(
        public Campaign $campaign,
        public CampaignEmail $email
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->campaign->subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.email-campaign',
            with: ['body' => $this->getBody()]
        );
    }
}
```

---

## Conceitos Importantes

### 1️⃣ Migrations (Migrações)

Migrations são arquivos que versionam o banco de dados, como "git" para o banco.

**Por quê?**

- Rastreia histórico de mudanças no banco
- Fácil compartilhar estrutura com outros desenvolvedores
- Pode fazer "rollback" se errar

**Exemplo:**

```php
// database/migrations/2026_04_20_185005_create_campaigns_table.php

Schema::create('campaigns', function (Blueprint $table) {
    $table->id();                    // id auto-increment
    $table->string('name');          // varchar(255)
    $table->foreignId('email_list_id')->constrained(); // Chave estrangeira
    $table->timestamps();            // created_at, updated_at
    $table->softDeletes();           // deleted_at
});
```

**Comandos úteis:**

```bash
php artisan migrate              # Executa todas as migrations
php artisan migrate:rollback     # Desfaz última migration
php artisan migrate:refresh      # Refaz tudo
```

### 2️⃣ Soft Deletes

Ao invés de deletar definitivamente, marca com `deleted_at`.

**Tabela com soft delete:**

```
+----+------+-------+-----------+
| id | name | email | deleted_at|
+----+------+-------+-----------+
| 1  | João |  ...  | NULL      |  ← Ativo
| 2  | Mari |  ...  | 2026-05.. |  ← Deletado, mas ainda lá!
+----+------+-------+-----------+
```

**Vantagens:**

- Recuperar dados deletados
- Auditoria (saber quem deletou quando)
- Dados históricos

**No código:**

```php
// Soft delete
$subscriber->delete();  // Apenas marca deleted_at

// Restaurar
$subscriber->restore();

// Buscar apenas ativos
Subscriber::all();  // NÃO inclui deletados

// Buscar tudo (incluindo deletados)
Subscriber::withTrashed()->all();

// Buscar APENAS deletados
Subscriber::onlyTrashed()->all();
```

### 3️⃣ Jobs (Filas Assíncronos)

Jobs são tarefas que rodam em background, não bloqueando a requisição HTTP.

**Por quê?**

- Enviar emails é lento
- Não queremos que usuário espere
- Pode fazer retry se falhar

**Como funciona:**

```
Requisição HTTP ─> Cria Job ─> Job entra na fila ─> Requisição retorna
                                      │
                                      ├─> Worker pega job
                                      ├─> Executa
                                      └─> Marca como concluído
```

**Tipos de Queue:**

- `sync`: Executa imediatamente (teste)
- `database`: Fila no banco (produção)
- `redis`: Fila em memória (alta performance)

### 4️⃣ Artisan (CLI do Laravel)

Artisan é a linha de comando do Laravel.

**Comandos principais:**

```bash
php artisan serve                  # Inicia servidor local
php artisan queue:listen           # Inicia worker de fila
php artisan tinker                 # REPL interativo
php artisan make:model ModelName   # Cria novo model
php artisan make:migration create_users_table  # Cria migration
php artisan db:seed                # Executa seeders
```

### 5️⃣ Blade (Template Engine)

Blade é o sistema de templates do Laravel. Usa `{{ }}` para variáveis.

**Exemplo:**

```blade
@foreach ($subscribers as $subscriber)
    <p>{{ $subscriber->name }} - {{ $subscriber->email }}</p>
@endforeach

@if ($campaign->track_open)
    <p>Rastreando aberturas</p>
@endif
```

### 6️⃣ Eloquent ORM

ORM (Object-Relational Mapping) facilita queries ao banco usando PHP.

**Exemplos:**

```php
// SELECT * FROM subscribers WHERE email_list_id = 1
$subscribers = Subscriber::where('email_list_id', 1)->get();

// SELECT * FROM subscribers LIMIT 10
$subscribers = Subscriber::limit(10)->get();

// SELECT * FROM subscribers WHERE name LIKE '%João%'
$subscribers = Subscriber::where('name', 'like', '%João%')->get();

// Relacionamentos (JOIN automático)
$emailList = EmailList::find(1);
$subscribers = $emailList->subscribers;  // Lazy loading

// Eager loading (mais eficiente)
$emailLists = EmailList::with('subscribers')->get();
```

### 7️⃣ Middleware

Middleware são filtros que executam antes/depois de requisições.

**Exemplo no projeto:**

```php
// CampaignCreateSessionControl middleware
// Valida se a sessão está correta antes de criar campanha
Route::get('campaigns/create/{tab?}', [CampaignController::class, 'create'])
    ->middleware(CampaignCreateSessionControl::class)
    ->name('campaigns.create');
```

### 8️⃣ Relações Eloquent

#### HasMany (Um para Muitos)

```php
// Um EmailList tem muitos Subscribers
public function subscribers(): HasMany
{
    return $this->hasMany(Subscriber::class);
}

// Uso:
$emailList->subscribers;  // Todos os subscribers da lista
```

#### BelongsTo (Muitos para Um)

```php
// Um Subscriber pertence a um EmailList
public function emailList(): BelongsTo
{
    return $this->belongsTo(EmailList::class);
}

// Uso:
$subscriber->emailList;  // A lista do subscriber
```

---

## Primeiros Passos

### 🚀 Configurar Ambiente

```bash
# 1. Clone o repositório
git clone <url-repo>
cd blastmail

# 2. Configure o arquivo .env
cp .env.example .env
# Edite as variáveis (database, mail, etc)

# 3. Instale dependências
composer install
npm install

# 4. Gere chave da aplicação
php artisan key:generate

# 5. Execute as migrations
php artisan migrate

# 6. (Opcional) Popule o banco com dados de teste
php artisan db:seed

# 7. Compile assets
npm run build

# 8. Inicie o servidor
composer run dev
```

### 📖 Entender o Fluxo

1. Leia `routes/web.php` para ver todas as rotas
2. Examine um controller, ex: `CampaignController.php`
3. Olhe o model correspondente, ex: `Campaign.php`
4. Verifique a view em `resources/views/campaigns/`
5. Teste no navegador

### 🧪 Testar Localmente

```bash
# Terminal 1: Servidor
php artisan serve

# Terminal 2: Queue worker
php artisan queue:listen

# Terminal 3: Logs em tempo real
php artisan pail

# Terminal 4: Vite (CSS/JS)
npm run dev
```

---

## Dicas e Boas Práticas

### ✅ Coisas Corretas

1. **Use Migrations**

    ```php
    // ✅ Correto: Usar migration
    Schema::create('campaigns', function (Blueprint $table) { ... });
    ```

2. **Use Relacionamentos Eloquent**

    ```php
    // ✅ Correto
    $emailList->subscribers;

    // ❌ Errado
    Subscriber::where('email_list_id', 1)->get();
    ```

3. **Use Eager Loading**

    ```php
    // ✅ Correto (1 query)
    $campaigns = Campaign::with('emailList', 'template')->get();

    // ❌ Errado (N+1 queries)
    $campaigns = Campaign::all();
    foreach ($campaigns as $campaign) {
        $campaign->emailList;  // Query para cada campaign
    }
    ```

4. **Use Soft Deletes Conscientemente**

    ```php
    // ✅ Correto
    $subscriber->delete();  // Soft delete

    // ❌ Evite (à menos que intencional)
    $subscriber->forceDelete();  // Delete permanente
    ```

5. **Valide Input**
    ```php
    // ✅ Correto
    $validated = $request->validate([
        'email' => 'required|email|unique:subscribers',
        'name' => 'required|string|max:255',
    ]);
    ```

### 📝 Estrutura de Arquivo

Sempre siga convenções do Laravel:

```
- Models: App\Models\NomeModelo
- Controllers: App\Http\Controllers\NomeController
- Jobs: App\Jobs\NomeJob
- Mail: App\Mail\NomeMailable
- Migrations: database/migrations/YYYY_MM_DD_HHMMSS_action.php
```

### 🐛 Debug

```php
// Usar dd() para debugar
dd($variable);  // Dump and die (printa e para)

// Usar dump() para printa sem parar
dump($variable);

// Ver queries SQL
\Illuminate\Support\Facades\DB::listen(function ($query) {
    echo $query->sql;
});
```

### 📚 Recursos Úteis

- **Documentação Oficial**: https://laravel.com/docs
- **Laravel Debugbar**: Já instalado, ver `/debugbar` durante debug
- **Tinker**: `php artisan tinker` para testar código interativo
- **Laravel Pail**: `php artisan pail` para ver logs em tempo real

---

## Próximos Passos

Agora que você conhece o projeto:

1. **Explore o código**: Leia alguns arquivos e tente entender
2. **Execute localmente**: Configure o ambiente e rode
3. **Tente pequenas mudanças**: Modifique um template ou adicione campo
4. **Leia testes**: Veja `tests/` para entender patterns
5. **Faça pull requests**: Contribua com melhorias!

---

## FAQ (Perguntas Frequentes)

### P: Por que softwares de email usam Soft Delete?

**R:** Porque dados históricos são importantes. Imagine uma campanha com 100k emails enviados - você não quer perder este histórico!

### P: Como o rastreamento de aberturas funciona?

**R:** Um pixel 1x1 invisível é inserido no email. Quando o cliente abre, o navegador faz uma requisição GET para `TrackingController::openings()`, registrando a abertura.

### P: Por que usar Jobs para enviar emails?

**R:** Porque enviar 10k emails leva tempo. Se fizéssemos na requisição HTTP, o usuário esperaria 10 minutos!

### P: O que é `cast()` no Model?

**R:** Converte tipos de dados. Ex: `'send_at' => 'datetime'` converte string em objeto Carbon.

### P: Qual a diferença entre `create()` e `save()`?

**R:**

```php
// create() - Menos código
Model::create(['name' => 'João']);

// save() - Mais controle
$model = new Model();
$model->name = 'João';
$model->save();
```

---

**Última atualização:** 22 de maio de 2026  
**Autor:** Time de Desenvolvimento  
**Versão:** 1.0

Boa sorte com o projeto! 🚀
