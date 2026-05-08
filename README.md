# EtecRead

EtecRead é um sistema web moderno e elegante para o gerenciamento de bibliotecas escolares. Ele foi projetado para Etecs (Escolas Técnicas Estaduais) para modernizar e digitalizar o catálogo de livros, empréstimos, reservas e o relacionamento entre alunos e a biblioteca.

O sistema adota o **Impeccable Design System**, que foca em layouts editoriais e minimalistas, providenciando uma interface extremamente limpa, rápida e acessível.

## Funcionalidades
- **Gestão de Acervo**: Cadastro completo de livros, autores e categorias.
- **Controle de Empréstimos**: Registro de saídas e devoluções com controle automático de datas e multas.
- **Sistema de Reservas**: Alunos podem solicitar reservas de livros de forma digital.
- **Painel Administrativo**: Área restrita para bibliotecários aprovarem reservas, cadastrarem usuários e checarem métricas de leitura.
- **Painel do Aluno**: Os alunos podem explorar o catálogo, ver suas atividades e administrar seus livros preferidos.

## Tecnologias Utilizadas
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS (via Vite), Alpine.js
- **Banco de Dados**: MySQL / SQLite (suportado pelo Eloquent)
- **Design System**: Impeccable (Tipografia forte, grids limpos e bordas sutis)

## Como rodar o projeto localmente

### Pré-requisitos
- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- Banco de dados (MySQL/MariaDB ou SQLite para desenvolvimento rápido)

### Passo a passo de instalação

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/EtecRead.git
   cd EtecRead
   ```

2. **Instale as dependências do PHP e do Node:**
   ```bash
   composer install
   npm install
   ```

3. **Configure o ambiente:**
   Copie o arquivo de exemplo e configure o banco de dados.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *No `.env`, altere `DB_CONNECTION` para o seu banco de dados ou use `sqlite` se preferir.*

4. **Rode as migrações e popule o banco (opcional):**
   ```bash
   php artisan migrate --seed
   ```

5. **Inicie os servidores locais:**
   Você precisará de dois terminais abertos: um para o PHP e um para o frontend (Vite).
   
   Terminal 1 (PHP):
   ```bash
   php artisan serve
   ```
   
   Terminal 2 (Vite):
   ```bash
   npm run dev
   ```

6. **Acesse o sistema:**
   Abra seu navegador em `http://localhost:8000`

---
*Desenvolvido com excelência para facilitar a difusão da leitura técnica.*
