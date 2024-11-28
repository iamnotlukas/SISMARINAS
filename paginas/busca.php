<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Busca de Embarcações</title>
  <style>
  /* Estilo geral */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
  }

  h1 {
    text-align: center;
    padding: 20px;
    margin: 0;
    background-color: #007BFF;
    color: white;
  }

  form {
    text-align: center;
    margin: 20px 0;
  }

  form input[type="text"] {
    width: 60%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
  }

  form button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }

  form button:hover {
    background-color: #0056b3;
  }

  #resultados {
    max-width: 90%;
    margin: 20px auto;
    padding: 10px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: white;
  }

  table thead {
    background-color: #007BFF;
    color: white;
  }

  table th,
  table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
  }

  table tbody tr:hover {
    background-color: #f1f1f1;
  }

  .cnpj {
    text-transform: uppercase;
  }

  /* Estilo geral para links */
  .botao-voltar {
    color: #007BFF;
    text-decoration: none;
    font-weight: bold;
    padding: 5px 10px;
    border: 1px solid #007BFF;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    display: table;
    margin: 0 auto;
  }

  /* Efeito hover nos links */
  .bota-voltar:hover {
    background-color: #007BFF;
    color: white;
  }
  </style>
  <script>
  function realizarBusca() {
    const termo = document.querySelector("input[name='termo']").value;

    fetch("processa_busca.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `termo=${encodeURIComponent(termo)}`
      })
      .then(response => response.text())
      .then(data => {
        document.getElementById("resultados").innerHTML = data;

        // Formatar CNPJ com JavaScript
        document.querySelectorAll('.cnpj').forEach(cnpj => {
          let texto = cnpj.textContent.replace(/\D/g, '');
          if (texto.length === 14) {
            cnpj.textContent = texto.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
          }
        });
      })
      .catch(error => {
        console.error("Erro na busca:", error);
      });

    return false; // Impede o reload da página
  }
  </script>
</head>

<body>
  <h1>Busca de Embarcações</h1>
  <form onsubmit="return realizarBusca();">
    <input type="text" name="termo" placeholder="Digite o termo de busca" required>
    <button type="submit">Buscar</button>
  </form>
  <div id="resultados"></div>
  <a href="op.php" class="botao-voltar">Voltar</a>
</body>

</html>