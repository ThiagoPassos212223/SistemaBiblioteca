<?php

    class Calculadora{
        // uso do operador +
        public double somar(double $n1, double $n2){
            return $n1 + $n2;
        }

        // uso do operador -
        public double subtrair(double $n1, double $n2){
            return $n1 - $n2;
        }

        // uso do operador *
        public double multiplicar(double $n1, double $n2){
            return $n1 * $n2;
        }

        // uso do operador /
        public ?double dividir(double $n1, double $n2){
            if ($n2 != 0){
                return $n1 / $n2;
            } else{
                return null;
            }
        }

        // uso do operador %
        public int restoDivisao(double $n1, double $n2){
            return $n1 % $n2;
        }

        // uso do looping for para somar todos os números em um intervalo
        public int somarIntervalo(int $n1, int $n2){
            int $resultado = 0;
            for (int $i = $n1; $i <= $n2; $i++){
                $resultado += $i;
            }
        }
    }
?>