<?php

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $db = mysqli_connect("localhost", "root", "", "cashflowdb");
} else if ($_SERVER['HTTP_HOST'] === 'cashflow.cakra-portfolio.my.id') {
    $db = mysqli_connect("localhost", "u686303384_cashflowdb", "#Cashflow12", "u686303384_cashflowdb");
}