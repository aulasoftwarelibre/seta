#language: es
@renew_a_rent
Característica: Renovar alquileres

  Reglas:

  - Las taquillas se pueden renovar solo desde 48 horas antes de la fecha de entrega
  - Una taquilla solo se puede renovar si su alquiler está marcado como renovable
  - Una taquilla que ya haya caducado no puede ser renovada

  Antecedentes:
    Dados los siguientes usuarios:
      | email           | dias_sancion  | comentario          |
      | john@gmail.com  |               |                     |
      | luis@gmail.com  | +7            | Entrega con retraso |
      | mary@gmail.com  |               |                     |
      | sara@gmail.com  |               |                     |
    Y las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 100             | alquilada     | mary@gmail.com  | -1    | +1    | sí        |
      | 101             | alquilada     | sara@gmail.com  | -7    | +3    | sí        |
      | 102             | alquilada     | john@gmail.com  | -5    | +2    | no        |
      | 103             | alquilada     | luis@gmail.com  | -15   | -8    | sí        |

  Escenario: Renovar una taquilla durante las 48 horas del día de entrega
    Cuando se quiere renovar el alquiler de la taquilla "100"
    Entonces el alquiler de la taquilla "100" caducará dentro de 8 días

  Escenario: Renovar una taquilla antes de las 48 horas del día de entrega
    Cuando se quiere renovar el alquiler de la taquilla "101"
    Entonces el alquiler de la taquilla "101" caducará dentro de 3 días

  Escenario: Renovar una taquilla de alquiler no renovable
    Cuando se quiere renovar el alquiler de la taquilla "102"
    Entonces el alquiler de la taquilla "102" caducará dentro de 2 días

  Escenario: Renovar una taquilla fuera de plazo
    Cuando se quiere renovar el alquiler de la taquilla "103"
    Entonces el alquiler de la taquilla "103" ha caducado


