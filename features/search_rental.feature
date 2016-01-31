#language: es
@rental
Característica: Buscar préstamos

  Antecedentes:
    Dados los siguientes centros:
      | nombre                                  | código    | activo  |
      | Escuela Politécnica Superior de Córdoba | epsc      | sí      |
      | Facultad de Ciencias                    | ciencias  | no      |
    Y los siguientes usuarios:
      | email           | centro        |
      | john@gmail.com  | epsc          |
      | luis@gmail.com  | epsc          |
      | mary@gmail.com  | epsc          |
      | sara@gmail.com  | ciencias      |
    Y las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 100             | alquilada     | mary@gmail.com  | -1    | +1    | sí        |
      | 101             | alquilada     | sara@gmail.com  | -7    | +2    | sí        |
      | 102             | alquilada     | john@gmail.com  | -5    | +2    | no        |
      | 103             | alquilada     | luis@gmail.com  | -15   | -2    | sí        |

  Esquema del escenario: Buscar alquileres por días
    Cuando buscamos los alquileres que van a caducar dentro de <días> días
    Entonces encontramos <total> alquileres
    Ejemplos:
      | días | total |
      |    1 |     1 |
      |    2 |     2 |
      |   -2 |     1 |
      |    3 |     0 |