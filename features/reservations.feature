#language: es
@reservations
Característica: Reservar y asignar taquillas

  Reglas:

  - Si no hay taquillas libres, la primera que vaya a caducar se marca como no
    renovable y se asigna al solicitante.
  - Si hay taquillas libres, se asigna al solicitante.
  - Si todas las taquillas están reservadas, se meten en cola sin asignar taquilla.
  - Las reservas tienen un plazo de 24 horas para confirmarlas y recogerlas.

  Antecedentes:
    Dados los siguientes usuarios:
      | email           | dias_sancion  | comentario          |
      | john@gmail.com  |               |                     |
      | luis@gmail.com  |               |                     |
      | mary@gmail.com  |               |                     |

  Escenario: Reservar una taquilla cuando no hay disponibles
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta |
      | 100             | no disponible |                 |       |       |
      | 101             | alquilada     | mary@gmail.com  | -1    | +1    |
      | 102             | alquilada     | john@gmail.com  | -5    | +2    |
    Cuando el usuario "luis@gmail.com" se mete en lista de espera
    Entonces el alquiler de la taquilla "101" es "no renovable"
    Y el usuario "luis@gmail.com" tiene una reserva para la taquilla "101"
    Y la reserva de la taquilla "101" tiene el estado "creada"

  Escenario: Reservar una taquilla cuando hay disponibles
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta |
      | 100             | disponible    |                 |       |       |
      | 101             | alquilada     | mary@gmail.com  | -1    | +1    |
      | 102             | alquilada     | john@gmail.com  | -5    | +2    |
    Cuando el usuario "luis@gmail.com" se mete en lista de espera
    Entonces el usuario "luis@gmail.com" tiene una reserva para la taquilla "100"
    Y la reserva de la taquilla "101" tiene el estado "creada"

  Escenario: Confirmar una reserva
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta |
      | 100             | reservada     |                 |       |       |
    Y las siguientes reservas:
      | código          | usuario       | estado          | desde |
      | 100             | luis@gmail.com| creada          | 0     |
    Cuando el usuario "luis@gmail.com" confirma su reserva
    Entonces la reserva de la taquilla "100" tiene el estado "confirmada"
