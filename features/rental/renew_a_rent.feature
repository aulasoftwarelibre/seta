#language: es
@renew_a_rent
Característica: Renovar alquileres

  Reglas:

  - Las taquillas se pueden renovar solo desde 48 horas antes de la fecha de entrega
  - Una taquilla solo se puede renovar si su alquiler está marcado como renovable
  - Una taquilla que ya haya caducado no puede ser renovada

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
      | sara@gmail.com  | epsc          |
      | anna@gmail.com  | ciencias      |
    Y las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 100             | alquilada     | mary@gmail.com  | -1    | +1    | sí        |
      | 101             | alquilada     | sara@gmail.com  | -7    | +3    | sí        |
      | 102             | alquilada     | john@gmail.com  | -5    | +2    | no        |
      | 103             | alquilada     | luis@gmail.com  | -15   | -8    | sí        |
      | 104             | alquilada     | anna@gmail.com  | -1    | +1    | sí        |

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

  Escenario: Renovar una taquilla de un usuario sancionado
    Dado las siguientes sanciones de tiempo:
      | usuario         | dias_sancion  |
      | mary@gmail.com  | 7             |
    Cuando se quiere renovar el alquiler de la taquilla "100"
    Entonces el alquiler de la taquilla "100" caducará dentro de 1 días

  Escenario: Renovar una taquilla de un centro bloqueado
    Cuando se quiere renovar el alquiler de la taquilla "104"
    Entonces el alquiler de la taquilla "104" caducará dentro de 1 días
