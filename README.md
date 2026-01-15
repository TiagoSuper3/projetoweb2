# 🛒 Loja Online – Projeto de Programação Web / Projeto Integrado III

Projeto de **loja online** desenvolvido em **PHP e MySQL**, no âmbito das unidades curriculares de **Programação Web** e **Projeto Integrado III**.

O sistema implementa funcionalidades completas de uma loja real, incluindo autenticação de utilizadores, carrinho de compras, checkout, gestão de encomendas e painel de administração.

🌐 **Website live (Hostinger):**  
👉 https://tiabra.antrob.eu

---

## 🔧 Funcionalidades Principais

- Registo, login e logout de utilizadores
- Gestão de sessão e permissões (user / admin)
- Listagem de produtos com **paginação via AJAX**
- Filtros de produtos (nome e preço) com **AJAX**
- Página individual de produto
- Carrinho de compras
- Checkout com validação de stock
- Encomendas associadas ao utilizador
- Visualização de tracking number
- Painel de administração:
  - gestão de produtos
  - edição de encomendas (estado e tracking)

---

## 🧱 Tecnologias Utilizadas

- PHP
- MySQL (PDO)
- HTML5
- CSS3
- JavaScript
- **AJAX**
- phpMyAdmin
- Hostinger (produção)

---

## 🔐 Segurança

- Passwords encriptadas (`password_hash`)
- Prepared Statements (PDO)
- Proteção CSRF em formulários sensíveis
- Separação de permissões (user / admin)

---

## ⚙️ Ambiente

O projeto distingue automaticamente entre:
- **Ambiente local (localhost)**
- **Ambiente de produção (Hostinger)**

A configuração é centralizada em `config/app.php`.

---

## 👨‍💻 Autor

Tiago Braga - 240001067
Projeto desenvolvido no contexto académico de Programação Web e Projeto Integrado III.