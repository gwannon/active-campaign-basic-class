<?php include_once("./config.php"); ?><html>
<head>
  <title>PANEL DE CONTROL</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <link href="./assests/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="./assests/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  <script src="./assests/js/jquery.min.js"></script>
  <link href="./assests/css/style.css" rel="stylesheet">
</head>
<body>
  <section class="p-3">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center p-2">Panel de control</h1>
      </div>
    </div>
  </section>
  <section class="p-3" style="background-color: #cecece;">
    <div class="row">
      <div class="col-md-3 col-lg-2 d-flex flex-column justify-content-center align-items-start">
        <button id="status-refresh" type="button" class="btn btn-primary mb-2 d-block" disabled="disabled">Comprobar de nuevo</button>
        <button id="migration" type="button" class="btn btn-primary mb-2 d-block">Encender/Apagar migracion</button>
        <button id="start-migration" type="button" class="btn btn-primary mb-2 d-block">Lanzar migración ahora</button>
      </div>
      <div class="col-md-9 col-lg-10 d-flex flex-column justify-content-center align-items-start">
        <p id="status-mssql" class="status rounded text-center p-2 mb-2" style="width: 100%;">Conexión MSSQL: <span>...</span></p>
        <p id="status-apiac" class="status rounded text-center p-2 mb-2" style="width: 100%;">Conexión API: <span>...</span></p>
        <p id="status-migration" class="status rounded text-center p-2 mb-2" style="width: 100%;">Estado Migración: <span>...</span></p>
      </div>
      <div class="col-12">
        <iframe id="migration-zone" style="background-color: #fff; display: none; width: 100%; min-height: 300px; border: 1px solid #000;"></iframe>
      </div>
    </div>
  </section>
  <section class="p-3" style="background-color: #fff;">
    <div class="row">
      <div class="col-md-3 col-lg-2 d-flex flex-column justify-content-start align-items-start">
        <button id="info-general" type="button" class="btn btn-primary mb-2 d-md-block">Información general</button>
        <button id="info-logs" type="button" class="btn btn-primary mb-2 d-md-block">Ver log</button>
        <button id="info-errors" type="button" class="btn btn-primary mb-2 d-md-block">Ver log de errores</button>
        <button id="info-tags" type="button" class="btn btn-primary mb-2 d-md-block">Información etiquetas</button>
        <button id="info-crons" type="button" class="btn btn-primary mb-2 d-md-block">Información crons</button>
      </div>
      <div class="col-md-9 col-lg-10 d-flex flex-column justify-content-start align-items-start">
        <table id="info-zone" class="table table-striped">
          <thead class="table-dark"></thead>
          <tbody></tbody>
        </table>
      </div>  
    </div>
  </section>
  <script src="./assests/js/scripts.js"></script>
</body>
</html>
