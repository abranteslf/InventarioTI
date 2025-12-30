# Sistema de Gerenciamento de Hardware (PHP)

Este projeto foi desenvolvido para treinamento da sintaxe do PHP e conceitos de Orientação a Objetos.
O objetivo é simular o controle de ativos de uma empresa, garantindo o rastreio da localização e do estado de conservação de cada item.

## Objetivos de Aprendizado
- Manipulação de **Herança** e **Polimorfismo** (Classes Notebook e Servidor).
- Gerenciamento de **Estado** (Pattern State ou lógica interna).
- Persistência de dados em arquivos (File System).

## Requisitos Funcionais (O que o sistema faz)
- [ ] **Cadastrar Equipamentos:** O sistema permite registrar equipamentos, especificamente das categorias **Notebook** e **Servidor**.
- [ ] **Listagem:** Deve ser capaz de listar todos os equipamentos cadastrados.
- [ ] **Alterar Status:** O usuário técnico deve conseguir atualizar o estado de conservação ou localização do ativo.

## Requisitos Não Funcionais (Restrições Técnicas)
- **Persistência:** Os dados devem ser armazenados localmente em um arquivo **JSON**.
- **Linguagem:** O projeto deve ser desenvolvido em **PHP Puro** (sem frameworks), versão 8.0 ou superior.
- **Interface:** O sistema deve rodar via CLI (Linha de Comando).