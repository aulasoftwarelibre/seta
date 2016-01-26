#language: es
@return_a_locker
Característica: Crear sanciones económicas
  Se pueden crear sanciones económicas a un usuario directamente o por
  medio de una sanción por un alquiler en concreto

  Reglas:
  - Una sanción puede tener asociada un alquiler o no
  - Una sanción siempre tiene asociada una taquilla
  - Debe indicar la cantidad por la que se sanciona

  Antecedentes:
    Dados los siguientes usuarios:
      | email           | dias_sancion  | comentario          |
      | mary@gmail.com  |               |                     |
    Y las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta |
      | 100             | alquilada     | mary@gmail.com  | -15   | -8    |
    Y la sanción por no devolver el candado de 2 euros

  Escenario: Sanción por devolución tardía y rotura de candado
    Cuando la taquilla "100" no es devuelta y se rompe el candado
    Entonces la taquilla "100" tiene el estado "disponible"
    Y el usuario "mary@gmail.com" tiene una sanción por la taquilla "100" de todo el curso
    Y el usuario "mary@gmail.com" tiene una sanción económica por la taquilla "100"
