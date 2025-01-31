# SISMARINAS

Este é um sistema desenvolvido para facilitar a gestão de marinas, embarcações e motoaquáticas. O objetivo principal é oferecer uma solução organizada, eficiente e de fácil acesso para controlar dados das marinas e seus ativos. Com este sistema, os administradores podem verificar rapidamente o status das embarcações e motoaquáticas, identificando irregularidades como status "ILEGAL" ou validade de documentos vencida bem como gerar relatórios.

## Objetivo

O sistema visa:
- Centralizar e organizar as informações de marinas, embarcações e motoaquáticas em uma única plataforma.
- Aumentar a eficiência operacional com consultas rápidas (menos de 1 segundo).
- Auxiliar na gestão de conformidade, destacando embarcações e motoaquáticas ilegais ou com documentos vencidos juntamente com o relatórios em pdf.

## Funcionalidades

- Cadastro, edição, listagem e exclusão de marinas.
- Registro de embarcações e motoaquáticas, com detalhes como nome, tipo, número de inscrição, status (LEGAL/ILEGAL), data de validade e observações.
- Consulta e destaque automático de embarcações ou motoaquáticas com irregularidades.
- Organização visual intuitiva para facilitar o uso e a navegação.
- Atualizações em tempo real sem necessidade de redirecionamento de página.
- Sistema de busca de embarcações, com algoritmo de semelhança de termo de busca (buscar tudo que seja semelhante ao termo usado para buscar)
- Gerar relatórios de marinas, emarcações e motoaquáticas que estiverem ilegais, semprecisar ver uma por uma
- Gerar relatório de todas as marinas cadastradas, juntamento com o número de marinas e marbarcações cadastrads no sistema

## Tecnologias Utilizadas

- **Linguagem de Programação**: PHP
- **Banco de Dados**: MySQL (com suporte a transações e integridade referencial)
- **Frontend**: HTML5, CSS3, JavaScript
- **Bibliotecas**: PDO para conexão segura ao banco de dados e TCPDF para gerar PDF
- **Servidor Local**: XAMPP (ou semelhante)

