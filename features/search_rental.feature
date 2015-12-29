#language: es
@rental
Característica: Buscar préstamos

  Antecedentes:
    Dados los siguientes usuarios:
      | email           | dias_sancion  | comentario          |
      | john@gmail.com  |               |                     |
      | luis@gmail.com  | +7            | Entrega con retraso |
      | mary@gmail.com  |               |                     |
      | sara@gmail.com  |               |                     |
    Y las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 100             | alquilada     | mary@gmail.com  | -1    | +1    | 1         |
      | 101             | alquilada     | sara@gmail.com  | -7    | +2    | 1         |
      | 102             | alquilada     | john@gmail.com  | -5    | +2    | 0         |
      | 103             | alquilada     | luis@gmail.com  | -15   | -2    | 1         |

  Esquema del escenario: Buscar alquileres por días
    Cuando buscamos los alquileres que van a caducar dentro de <días> días
    Entonces encontramos <total> alquileres
    Ejemplos:
      | días | total |
      |    1 |     1 |
      |    2 |     2 |
      |   -2 |     1 |
      |    3 |     0 |