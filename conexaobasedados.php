<?php

// para fazer a conexão é preciso: hostname, utilizador da bd, senha, nome da bd

$_conn = mysqli_connect("esilxl0nthgloe1y.chr7pe7iynqr.eu-west-1.rds.amazonaws.com","xsmm0mql1737lenr","okdb98imduxv77w0","xlyx68dqr91tyr5f");

// Verificar se a conexão correu bem...
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$_conn->set_charset('utf8');
